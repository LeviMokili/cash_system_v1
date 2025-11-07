<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Transfer - Cash Transfer System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --bg: #f1f5f9;
            --text: #0f172a;
            --border: #e2e8f0;
            --success: #16a34a;
            --error: #dc2626;
            --warning: #f59e0b;
            --radius: 0px;
            --shadow: 0 6px 16px rgba(0,0,0,0.08);
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: var(--bg);
            margin: 0;
            padding: 0;
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: white;
            width: 90%;
            max-width: 900px;
            margin: 3rem auto;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
        }

        h2 {
            text-align: center;
            font-size: 1.8rem;
            color: var(--primary-dark);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem 2rem;
        }

        label {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #f9fafb;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            outline: none;
            background-color: white;
        }

        input:read-only {
            background-color: #e2e8f0;
            cursor: not-allowed;
        }

        .success, .error {
            grid-column: span 2;
            padding: 1rem;
            border-radius: var(--radius);
            font-weight: 500;
            text-align: center;
        }

        .success {
            background: #dcfce7;
            color: var(--success);
        }

        .error {
            background: #fee2e2;
            color: var(--error);
        }

        .full {
            grid-column: span 2;
        }

        .btn-group {
            grid-column: span 2;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            padding: 1rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: var(--radius);
            padding: 1rem 2rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease-in-out;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: var(--warning);
        }

        .btn-warning:hover {
            background: #d97706;
        }

        a.back {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        a.back:hover {
            color: var(--primary);
        }

        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .transfer-info {
            grid-column: span 2;
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: var(--radius);
            border-left: 4px solid var(--primary);
            margin-bottom: 1.5rem;
        }

        .transfer-info h3 {
            margin: 0 0 1rem 0;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: var(--text);
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            .btn-group {
                flex-direction: column;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Transfer Entry</h2>

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="transfer-info">
            <h3>📋 Transfer Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Reference Code:</span>
                    <span class="info-value">{{ $transfer->reference_code }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created At:</span>
                    <span class="info-value">{{ $transfer->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ $transfer->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('transfers.update', $transfer->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div>
                <label for="date_transfer">Transfer Date</label>
                <input id="date_transfer" name="date_transfer" type="date" 
                       value="{{ old('date_transfer', $transfer->date_transfer->format('Y-m-d')) }}" required>
                @error('date_transfer')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="reference_code">Reference Code</label>
                <input id="reference_code" name="reference_code" type="text" 
                       value="{{ $transfer->reference_code }}" readonly>
                <small style="color: #6b7280; font-size: 0.875rem;">Reference code cannot be changed</small>
            </div>

            <div>
                <label for="sender_name">Sender Name</label>
                <input id="sender_name" name="sender_name" type="text" 
                       value="{{ old('sender_name', $transfer->sender_name) }}" required>
                @error('sender_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="receiver_name">Receiver Name</label>
                <input id="receiver_name" name="receiver_name" type="text" 
                       value="{{ old('receiver_name', $transfer->receiver_name) }}" required>
                @error('receiver_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="ville_provenance">Ville de Provenance</label>
                <input id="ville_provenance" name="ville_provenance" type="text" 
                       value="{{ old('ville_provenance', $transfer->ville_provenance) }}" required>
                @error('ville_provenance')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="ville_destination">Ville de Destination</label>
                <input id="ville_destination" name="ville_destination" type="text" 
                       value="{{ old('ville_destination', $transfer->ville_destination) }}" required>
                @error('ville_destination')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="guichetier_provenance">Guichetier Provenance</label>
                <input id="guichetier_provenance" name="guichetier_provenance" type="text" 
                       value="{{ old('guichetier_provenance', $transfer->guichetier_provenance) }}" required>
                @error('guichetier_provenance')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="guichetier_destination">Guichetier Destination</label>
                <input id="guichetier_destination" name="guichetier_destination" type="text" 
                       value="{{ old('guichetier_destination', $transfer->guichetier_destination) }}" required>
                @error('guichetier_destination')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="amount">Amount (USD)</label>
                <input id="amount" name="amount" type="number" step="0.01" min="0" 
                       value="{{ old('amount', $transfer->amount) }}" required>
                @error('amount')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

         

            <div class="btn-group">
                <button type="submit" class="btn-warning">
                    <i class="fas fa-save"></i> Update Transfer
                </button>
                <a href="{{ route('user1.dashboard') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>

        <a class="back" href="{{ route('transfers.show', $transfer->id) }}">
            <i class="fas fa-eye"></i> View Transfer Details
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Edit transfer page loaded');
        });
    </script>
</body>
</html>