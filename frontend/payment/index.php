<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tích hợp VNPAY Sandbox</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .payment-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .payment-card h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #0056b3; }
        .note { font-size: 12px; color: #777; margin-top: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="payment-card">
    <h2>Thanh toán VNPAY</h2>
    <form action="vnpay_create_payment.php" method="POST">
        
        <div class="form-group">
            <label for="amount">Số tiền (VNĐ):</label>
            <input type="number" id="amount" name="amount" value="10000" min="10000" required>
        </div>

        <div class="form-group">
            <label for="order_desc">Nội dung thanh toán:</label>
            <input type="text" id="order_desc" name="order_desc" value="Thanh toan test 01" required>
        </div>

        <div class="form-group">
            <label for="bank_code">Ngân hàng (Demo):</label>
            <select name="bank_code" id="bank_code">
                <option value="">Không chọn (VNPAY tự chọn)</option>
                <option value="NCB" selected>NCB (Ngân hàng test)</option>
                <option value="AGRIBANK">Agribank</option>
                <option value="MBAPP">MBBank App</option>
            </select>
        </div>

        <button type="submit" name="redirect" class="btn-submit">Thanh toán ngay</button>
        
        <div class="note">
            <p>Môi trường Sandbox (Test)</p>
        </div>
    </form>
</div>

</body>
</html>