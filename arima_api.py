from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
from statsmodels.tsa.arima.model import ARIMA
import warnings

warnings.filterwarnings("ignore")  # Hide warning untuk demo

app = Flask(__name__)
CORS(app)  # Aktifkan CORS

@app.route('/arima-predict', methods=['POST'])
def arima_predict():
    try:
        data = request.json.get('data')
        periods = int(request.json.get('periods', 4))
        
        # Konversi ke DataFrame
        df = pd.DataFrame(data)
        df = df.sort_values('year')
        
        # Persiapkan data time series
        series = pd.Series(
            df['value'].values,
            index=pd.to_datetime(df['year'].astype(str), format='%Y')
        )
        
        # Train ARIMA model
        model = ARIMA(series, order=(1,1,1))
        model_fit = model.fit()
        
        # Buat prediksi
        forecast = model_fit.forecast(steps=periods)
        
        # Format output
        predictions = [{
            'year': int(series.index[-1].year + i + 1),
            'value': round(float(forecast[i]), 2)
        } for i in range(periods)]
        
        return jsonify({'status': 'success', 'predictions': predictions})
    
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
