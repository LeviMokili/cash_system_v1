<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Transfer - Cash Transfer System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/css/intlTelInput.css">
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
            --radius: 0px;
            --shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
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

        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #f9fafb;
        }

        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #f9fafb;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            outline: none;
            background-color: white;
        }

        input:read-only {
            background-color: #e2e8f0;
            cursor: not-allowed;
        }

        .success,
        .error {
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

        button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            padding: 1rem;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
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

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>💸 New Cash Transfer Entry</h2>

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('transfers.store') }}" novalidate>
            @csrf

            <div>
                <label for="date_transfer">Transfer Date</label>
                <input id="date_transfer" name="date_transfer" type="date"
                    value="{{ old('date_transfer', date('Y-m-d')) }}" required onchange="generateReferenceCode()">
                @error('date_transfer')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="reference_code">Reference Code</label>
                <input id="reference_code" name="reference_code " type="text" value="{{ old('reference_code') }}"
                    readonly required>
                @error('reference_code')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="sender_name">Sender Name</label>
                <input id="sender_name" name="sender_name" type="text" value="{{ old('sender_name') }}" required>
                @error('sender_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="receiver_name">Receiver Name</label>
                <input id="receiver_name" name="receiver_name" type="text" value="{{ old('receiver_name') }}" required>
                @error('receiver_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>


                <label for="ville_provenance">Ville de Provenance</label>
                <select id="ville_provenance" name="ville_provenance">
                    <option value="Butembo 1">Butembo 1</option>
                    <option value="Butembo 2">Banana</option>
                    <option value="Beni">Beni</option>
                    <option value="Bunia">Bunia</option>
                    <option value="Durba">Durba</option>
                    <option value="Arua">Arua</option>
                    <option value="Kisangani">Kisangani</option>
                    <option value="Kinshasa">Kinshasa</option>
                    <option value="Goma">Goma</option>
                    <option value="Bukavu">Bukavu</option>
                    <option value="Isiro">Isiro</option>
                    <option value="Kampala">Kampala</option>
                    <option value="Daresalam">Daresalam</option>
                    <option value="Nairobi">Daresalam</option>
                    <option value="China">China</option>
                    <option value="Dubai">Dubai</option>
                    <option value="India">India</option>
                    <option value="Moku">Moku</option>
                    <option value="Wanga">Moku</option>
                </select>
                @error('ville_provenance')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="ville_destination">Ville destination</label>
                <select id="ville_destination" name="ville_destination">
                    <option value="Butembo 1">Butembo 1</option>
                    <option value="Butembo 2">Banana</option>
                    <option value="Beni">Beni</option>
                    <option value="Bunia">Bunia</option>
                    <option value="Durba">Durba</option>
                    <option value="Arua">Arua</option>
                    <option value="Kisangani">Kisangani</option>
                    <option value="Kinshasa">Kinshasa</option>
                    <option value="Goma">Goma</option>
                    <option value="Bukavu">Bukavu</option>
                    <option value="Isiro">Isiro</option>
                    <option value="Kampala">Kampala</option>
                    <option value="Daresalam">Daresalam</option>
                    <option value="Nairobi">Daresalam</option>
                    <option value="China">China</option>
                    <option value="Dubai">Dubai</option>
                    <option value="India">India</option>
                    <option value="Moku">Moku</option>
                    <option value="Wanga">Moku</option>
                </select>
                @error('ville_destination')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="guichetier_provenance">Guichetier Provenance</label>
                <input id="guichetier_provenance" name="guichetier_provenance" type="text"
                    value="{{ old('guichetier_provenance') }}" required>
                @error('guichetier_provenance')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="guichetier_destination">Guichetier Destination</label>
                <input id="guichetier_destination" name="guichetier_destination" type="text"
                    value="{{ old('guichetier_destination') }}" required>
                @error('guichetier_destination')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="full">
                <label for="telephone">Telehone</label>
                <input id="telephone" name="telephone" type="tel" step="0.01" min="0" value="{{ old('telephone') }}"
                    required>
                @error('telephone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- <div class="full">
              <input type="tel" id="phone" class="phone-input" placeholder="Enter your phone number" >
            </div> -->


            <div class="full">
                <label for="amount">Amount (UGX)</label>
                <input id="amount" name="amount" type="number" step="0.01" min="0" value="{{ old('amount') }}" required>
                @error('amount')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>


            <div class="full">
                <button type="submit">💾 Save Transfer</button>
            </div>
        </form>

        <a class="back" href="">← Back to Dashboard</a>




    </div>






    <script>
        $(document).ready(function () {
            // Initialize intl-tel-input
            const phoneInput = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "no",
                preferredCountries: ["no", "us", "gb", "de", "fr", "se"],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
            });

            // Update phone info when country changes
            phoneInput.addEventListener("countrychange", function () {
                updatePhoneInfo();
            });

            // Update phone info when input changes
            $(phoneInput).on('input', function () {
                updatePhoneInfo();
                validatePhone();
            });

            // Function to update phone information
            function updatePhoneInfo() {
                const countryData = iti.getSelectedCountryData();
                const isValid = iti.isValidNumber();

                let infoText = `Country: ${countryData.name} (${iti.getSelectedDialCode()})`;

                if (isValid) {
                    const number = iti.getNumber();
                    infoText += ` | Valid: Yes | Format: ${number}`;
                    $('#phoneInfo').css('color', '#27ae60');
                } else {
                    infoText += ' | Valid: No';
                    $('#phoneInfo').css('color', '#e74c3c');
                }

                $('#phoneInfo').text(infoText);
            }

            // Function to validate phone number


            // Initialize jQuery Validation
            $('#phoneForm').validate({
                rules: {
                    phone: {
                        required: true,
                        // Custom validation using intl-tel-input
                        validatePhone: true
                    }
                },
                messages: {
                    phone: {
                        required: "Please enter a phone number",
                        validatePhone: "Please enter a valid phone number"
                    }
                },
                errorElement: 'div',
                errorClass: 'error',
                errorPlacement: function (error, element) {
                    error.appendTo($('#phoneError'));
                },
                submitHandler: function (form) {
                    if (validatePhone()) {
                        const phoneNumber = iti.getNumber();

                        // Show success message with animation
                        $('#successMessage').fadeIn(300);

                        // Reset form after 3 seconds
                        setTimeout(function () {
                            $('#successMessage').fadeOut(300);
                            form.reset();
                            iti.setCountry('no');
                            updatePhoneInfo();
                        }, 3000);

                        // In a real application, you would submit the form data here
                        console.log(`Phone number submitted: ${phoneNumber}`);
                    }
                    return false;
                }
            });




            // Initialize phone info
            updatePhoneInfo();
        });
    </script>
    <script>
        // Generate reference code when page loads and when date changes
        document.addEventListener('DOMContentLoaded', function () {
            generateReferenceCode();
        });

        function generateReferenceCode() {
            const dateInput = document.getElementById('date_transfer');
            const referenceInput = document.getElementById('reference_code');

            if (!dateInput.value) {
                referenceInput.value = '';
                return;
            }

            // Show loading state
            referenceInput.classList.add('loading');

            fetch('{{ route("transfers.get-reference") }}?date=' + dateInput.value, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    referenceInput.value = data.reference_code;
                    referenceInput.classList.remove('loading');
                })
                .catch(error => {
                    console.error('Error generating reference code:', error);
                    referenceInput.classList.remove('loading');
                });
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/js/intlTelInput.min.js"></script>
    <script>
        const input = document.querySelector("#phone");
        window.intlTelInput(input, {
            loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/js/utils.js"),
        });
    </script>
</body>

</html>