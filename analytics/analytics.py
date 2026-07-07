from flask import Flask, jsonify, request, abort
from sqlalchemy import create_engine, text
import pandas as pd
from functools import wraps
from dotenv import load_dotenv
import os

API_KEY = os.getenv('ANALYTICS_API_KEY')

def require_api_key(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        provided_key = request.headers.get('X-API-Key')
        if not provided_key or provided_key != API_KEY:
            abort(401, description="Unauthorized: invalid or missing API key")
        return f(*args, **kwargs)
    return decorated

load_dotenv()

app = Flask(__name__)

DB_USER = os.getenv('DB_USER', 'root')
DB_PASSWORD = os.getenv('DB_PASSWORD', '')
DB_HOST  = os.getenv('DB_HOST', '127.0.0.1')
DB_NAME = os.getenv('DB_NAME', 'aracorp-pos')

engine = create_engine(f'mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}/{DB_NAME}')

#This is for Sales Summary
@app.route('/analytics/summary', methods=['GET'])
@require_api_key
def sales_summary():
    df = pd.read_sql('SELECT * FROM transactions', engine)

    if df.empty:
        return jsonify({
            'total_revenue': 0,
            'total_transactions' : 0,
            'average_transaction': 0,
        })
    
    return jsonify({
        'total_revenue': int(df['total_price'].sum()),
        'total_transactions': len(df),
        'average_transaction': int(df['total_price'].mean()),
    })

#This is for best selling products
@app.route('/analytics/best-sellers', methods=['GET'])
@require_api_key
def best_sellers():
    query = '''
        SELECT 
            p.name,
            SUM(ti.quantity) as total_sold,
            SUM(ti.subtotal) as total_revenue
        FROM transaction_items ti
        JOIN products p ON p.id = ti.product_id
        GROUP BY p.id, p.name
        ORDER BY total_sold DESC
        LIMIT 5
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify([])
    
    return jsonify(df.to_dict(orient='records'))

#3. Daily revenue (last 7 days)
@app.route('/analytics/daily-revenue', methods=['GET'])
@require_api_key
def daily_revenue():
    query = '''
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as total_transactions,
            SUM(total_price) as revenue
        FROM transactions
        GROUP BY DATE(created_at)
        ORDER BY date DESC
        LIMIT 7
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify([])
    
    df['date'] = df['date'].astype(str)
    df['revenue'] = df['revenue'].astype(int)

    return jsonify(df.to_dict(orient='records'))

#4. Low stock products
@app.route('/analytics/low-stock', methods=['GET'])
@require_api_key
def low_stock():
    query = '''
        SELECT name, stock, price
        FROM products
        WHERE stock <= 10
        ORDER BY stock ASC
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify([])
    
    return jsonify(df.to_dict(orient='records'))

#5. Today's summary
@app.route('/analytics/today', methods=['GET'])
@require_api_key
def today_summary():
    query = '''
        SELECT 
            COUNT(*) as total_transactions,
            COALESCE(SUM(total_price), 0) as revenue
        FROM transactions
        WHERE DATE(created_at) = CURDATE()
    '''
    df = pd.read_sql(query, engine)

    return jsonify({
        'total_transactions': int(df['total_transactions'][0]),
        'revenue': int(df['revenue'][0]),
    })

# ── 6. Revenue Line Chart (last 14 days) ──────────
@app.route('/analytics/revenue-chart', methods=['GET'])
@require_api_key
def revenue_chart():
    query = '''
        SELECT 
            DATE(created_at) as date,
            SUM(total_price) as revenue
        FROM transactions
        GROUP BY DATE(created_at)
        ORDER BY date ASC
        LIMIT 14
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify({'dates': [], 'values': []})

    df['date'] = df['date'].astype(str)
    df['revenue'] = df['revenue'].astype(int)

    return jsonify({
        'dates': df['date'].tolist(),
        'values': df['revenue'].tolist()
    })

# ── 7. Category Pie Chart ─────────────────────────
@app.route('/analytics/category-chart', methods=['GET'])
@require_api_key
def category_chart():
    query = '''
        SELECT 
            COALESCE(p.category, 'Uncategorized') as category,
            SUM(ti.subtotal) as revenue
        FROM transaction_items ti
        JOIN products p ON p.id = ti.product_id
        GROUP BY p.category
        ORDER BY revenue DESC
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify({'labels': [], 'values': []})

    return jsonify({
        'labels': df['category'].tolist(),
        'values': df['revenue'].astype(int).tolist()
    })

# ── 8. Hourly Sales Bar Chart ─────────────────────
@app.route('/analytics/hourly-chart', methods=['GET'])
@require_api_key
def hourly_chart():
    query = '''
        SELECT 
            HOUR(created_at) as hour,
            COUNT(*) as transactions,
            SUM(total_price) as revenue
        FROM transactions
        GROUP BY HOUR(created_at)
        ORDER BY hour ASC
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify({'hours': [], 'transactions': [], 'revenue': []})

    # Fill missing hours with 0
    all_hours = pd.DataFrame({'hour': range(24)})
    df = all_hours.merge(df, on='hour', how='left').fillna(0)

    return jsonify({
        'hours': [f"{int(h):02d}:00" for h in df['hour'].tolist()],
        'transactions': df['transactions'].astype(int).tolist(),
        'revenue': df['revenue'].astype(int).tolist()
    })

# ── 9. Profit Summary ─────────────────────────────
@app.route('/analytics/profit-summary', methods=['GET'])
@require_api_key
def profit_summary():
    query = '''
        SELECT 
            SUM(subtotal) as revenue,
            SUM(quantity * cost_price) as cogs
        FROM transaction_items
    '''
    df = pd.read_sql(query, engine)

    if df.empty or df['revenue'][0] is None:
        return jsonify({
            'revenue': 0,
            'cogs': 0,
            'gross_profit': 0,
            'margin_percent': 0
        })

    revenue = float(df['revenue'][0] or 0)
    cogs = float(df['cogs'][0] or 0)
    gross_profit = revenue - cogs
    margin = (gross_profit / revenue * 100) if revenue > 0 else 0

    return jsonify({
        'revenue': int(revenue),
        'cogs': int(cogs),
        'gross_profit': int(gross_profit),
        'margin_percent': round(margin, 1)
    })

# ── 10. Today's Profit ────────────────────────────
@app.route('/analytics/profit-today', methods=['GET'])
@require_api_key
def profit_today():
    query = '''
        SELECT 
            SUM(ti.subtotal) as revenue,
            SUM(ti.quantity * ti.cost_price) as cogs
        FROM transaction_items ti
        JOIN transactions t ON t.id = ti.transaction_id
        WHERE DATE(t.created_at) = CURDATE()
    '''
    df = pd.read_sql(query, engine)

    if df.empty or df['revenue'][0] is None:
        return jsonify({'gross_profit': 0, 'margin_percent': 0})

    revenue = float(df['revenue'][0] or 0)
    cogs = float(df['cogs'][0] or 0)
    gross_profit = revenue - cogs
    margin = (gross_profit / revenue * 100) if revenue > 0 else 0

    return jsonify({
        'gross_profit': int(gross_profit),
        'margin_percent': round(margin, 1)
    })

# ── 11. Profit Trend (last 14 days) ──────────────
@app.route('/analytics/profit-trend', methods=['GET'])
@require_api_key
def profit_trend():
    query = '''
        SELECT 
            DATE(t.created_at) as date,
            SUM(ti.subtotal) as revenue,
            SUM(ti.quantity * ti.cost_price) as cogs
        FROM transaction_items ti
        JOIN transactions t ON t.id = ti.transaction_id
        GROUP BY DATE(t.created_at)
        ORDER BY date ASC
        LIMIT 14
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify({'dates': [], 'profit': [], 'revenue': []})

    df['date'] = df['date'].astype(str)
    df['revenue'] = df['revenue'].fillna(0).astype(int)
    df['cogs'] = df['cogs'].fillna(0).astype(int)
    df['profit'] = df['revenue'] - df['cogs']

    return jsonify({
        'dates': df['date'].tolist(),
        'profit': df['profit'].tolist(),
        'revenue': df['revenue'].tolist()
    })

# ── 12. Profit By Product ─────────────────────────
@app.route('/analytics/profit-by-product', methods=['GET'])
@require_api_key
def profit_by_product():
    query = '''
        SELECT 
            p.name,
            SUM(ti.subtotal) as revenue,
            SUM(ti.quantity * ti.cost_price) as cogs
        FROM transaction_items ti
        JOIN products p ON p.id = ti.product_id
        GROUP BY p.id, p.name
        ORDER BY revenue DESC
        LIMIT 5
    '''
    df = pd.read_sql(query, engine)

    if df.empty:
        return jsonify([])

    df['revenue'] = df['revenue'].fillna(0).astype(int)
    df['cogs'] = df['cogs'].fillna(0).astype(int)
    df['gross_profit'] = df['revenue'] - df['cogs']
    df['margin_percent'] = df.apply(
        lambda r: round((r['gross_profit'] / r['revenue'] * 100), 1) if r['revenue'] > 0 else 0,
        axis=1
    )

    return jsonify(df.to_dict(orient='records'))

if __name__ == '__main__':
    app.run(port=5001, debug=True)