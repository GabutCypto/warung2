<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
        .card h4 {
            font-size: 24px;
            font-weight: bold;
        }
        .card p {
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="card">
    <img src="{{ Storage::url($kartu->photo) }}" alt="{{ $kartu->name }}">
    <h4>{{ $kartu->name }}</h4>
    <p>{{ $kartu->notes }}</p>
    <!-- Add other details as needed -->
</div>

<script>
    window.print();
</script>

</body>
</html>
