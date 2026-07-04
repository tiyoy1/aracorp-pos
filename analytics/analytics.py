from flask import Flask, jsonify
from sqlalchemy import create_engine, text
import pandas as pd
from dotenv import load_dotenv
import os

load_dotenv()

app = Flask(__name__)

DB_USER = os.getenv('DB_USER', 'root')
DB_PASSWORD = os.getenv('DB_PASSWORD', '')
DB_HOST  = os.getenv('DB_HOST', '127.0.0.1')
DB_NAME = os.getenv('DB_NAME', 'aracorp-pos')

engine = create_engine(f'mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}/{DB_NAME}')

#This is for Sales Summary
@app.route('/analytics/summary', methods=['GET'])
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

if __name__ == '__main__':
    app.run(port=5001, debug=True)