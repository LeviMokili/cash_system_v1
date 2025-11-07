{{-- resources/views/user1/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard | Cash Transfer System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d9d5a64b.js" crossorigin="anonymous"></script>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

  <style>
    :root {
      --primary: #2563eb;
      --secondary: #f9fafb;
      --text-dark: #1f2937;
      --text-light: #6b7280;
      --success: #16a34a;
      --warning: #f59e0b;
      --danger: #dc2626;
      --radius: 14px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Poppins", sans-serif;
      background: #f1f5f9;
      color: var(--text-dark);
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: var(--primary);
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h2 {
      margin: 0 0 30px;
      font-weight: 600;
      text-align: center;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: #e5e7eb;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 8px;
      border-radius: var(--radius);
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .sidebar a i {
      margin-right: 10px;
      font-size: 18px;
    }

    .logout {
      margin-top: auto;
      background: #ef4444;
      text-align: center;
      padding: 12px;
      border-radius: var(--radius);
      color: white;
      font-weight: 500;
      text-decoration: none;
    }

    .logout:hover {
      background: #b91c1c;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    header h2 {
      margin: 0;
      font-size: 24px;
      color: var(--primary);
    }

    .btn {
      background: var(--primary);
      color: #fff;
      border: none;
      padding: 12px 20px;
      border-radius: var(--radius);
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
    }

    .btn:hover {
      background: #1e40af;
      transform: translateY(-2px);
    }

    /* Stats Cards */
    .summary-widgets {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .widget {
      background: white;
      border-radius: var(--radius);
      padding: 25px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.2s ease;
    }

    .widget:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .widget i {
      font-size: 32px;
    }

    .widget h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 600;
    }

    .widget p {
      margin: 4px 0 0;
      color: var(--text-light);
      font-size: 14px;
    }

    .widget.total i {
      color: var(--primary);
    }

    .widget.pending i {
      color: var(--warning);
    }

    .widget.confirmed i {
      color: var(--success);
    }

    .widget.cancelled i {
      color: var(--danger);
    }

    /* DataTable Custom Styling */
    .dataTables_wrapper {
      background: white;
      border-radius: var(--radius);
      padding: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .dataTables_filter {
      margin-bottom: 15px;
    }

    .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0px;
      padding: 8px 12px;
      font-size: 14px;
      width: 250px;
    }

    .dataTables_filter input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .dataTables_length {
      margin-bottom: 15px;
    }

    .dataTables_length select {
      border: 1px solid #d1d5db;
      border-radius: 0px;
      padding: 6px 30px;
      margin: 0 8px;
    }

    .dataTables_info {
      color: var(--text-light);
      font-size: 14px;
      margin-top: 10px;
    }

    table.dataTable {
      border-collapse: collapse !important;
      margin: 15px 0 !important;
    }

    table.dataTable thead th {
      background: var(--primary) !important;
      color: white !important;
      border: none !important;
      padding: 12px 16px !important;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 13px;
    }

    table.dataTable tbody td {
      padding: 12px 16px !important;
      border-bottom: 1px solid #f3f4f6 !important;
    }

    table.dataTable tbody tr:hover {
      background-color: #f8fafc !important;
    }

    /* Improved Pagination Styling */
    .dataTables_paginate {
      margin-top: 20px !important;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .dataTables_paginate .paginate_button {
      border: 1px solid #d1d5db !important;
      border-radius: 8px !important;
      padding: 8px 14px !important;
      margin: 0 4px !important;
      font-size: 10px;
      font-weight: 200;
      transition: all 0.2s ease;
      min-width: 42px;
      text-align: center;
      display: inline-block;
    }

    .dataTables_paginate .paginate_button.current {
      background: var(--primary) !important;
      color: white !important;
      border-color: var(--primary) !important;
      box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .dataTables_paginate .paginate_button:hover:not(.current) {
      background: #f3f4f6 !important;
      border-color: #9ca3af !important;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
      padding: 8px 16px !important;
      margin: 0 8px !important;
      font-weight: 600;
      background: #f8fafc !important;
    }

    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled:hover {
      background: #f3f4f6 !important;
      color: #9ca3af !important;
      border-color: #e5e7eb !important;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .dataTables_paginate .ellipsis {
      padding: 8px 6px !important;
      margin: 0 4px !important;
      color: #6b7280 !important;
      border: none !important;
      background: none !important;
    }

    /* Mobile responsive pagination */
    @media (max-width: 768px) {
      .dataTables_paginate .paginate_button {
        padding: 6px 10px !important;
        margin: 0 3px !important;
        min-width: 36px;
        font-size: 13px;
      }

      .dataTables_paginate .paginate_button.previous,
      .dataTables_paginate .paginate_button.next {
        padding: 6px 12px !important;
        margin: 0 6px !important;
      }

      .dataTables_paginate {
        flex-wrap: wrap;
        gap: 5px;
      }

      .dataTables_filter input {
        width: 200px;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <h2><i class="fas fa-money-bill-transfer"></i> CashFlow</h2>
    <a href="{{ route('user1.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
    <a href="{{ route('transfers.create') }}"><i class="fas fa-plus-circle"></i> New Transfer</a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>

  <div class="main">
    <header>
      <h2>Welcome, {{ Auth::user()->name }} 👋</h2>
      <a href="{{ route('transfers.create') }}" class="btn">
        <i class="fas fa-plus-circle"></i> New Transfer
      </a>
    </header>

    @if(session('success'))
      <div class="alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Summary Widgets -->
    <div class="summary-widgets">
      <div class="widget total">
        <i class="fas fa-calendar-day"></i>
        <div>
          <h3>{{ $stats['totalToday'] }}</h3>
          <p>Transfers Today</p>
        </div>
      </div>
      <div class="widget pending">
        <i class="fas fa-hourglass-half"></i>
        <div>
          <h3>{{ $stats['pendingCount'] }}</h3>
          <p>Pending</p>
        </div>
      </div>
      <div class="widget confirmed">
        <i class="fas fa-check-circle"></i>
        <div>
          <h3>{{ $stats['confirmedCount'] }}</h3>
          <p>Confirmed</p>
        </div>
      </div>
      <div class="widget cancelled">
        <i class="fas fa-times-circle"></i>
        <div>
          <h3>{{ $stats['cancelledCount'] }}</h3>
          <p>Cancelled</p>
        </div>
      </div>
    </div>

    <!-- Recent Transfers with DataTable -->
    <div class="table-header"
      style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h3 style="color:var(--primary); margin: 0;">
        <i class="fas fa-exchange-alt"></i> Recent Transfers
      </h3>
      <div class="table-actions">
        <button id="refresh-table" class="btn" style="background: #10b981; padding: 8px 16px;">
          <i class="fas fa-sync-alt"></i> Refresh
        </button>
      </div>
    </div>

    @if($recentTransfers->count() > 0)
      <div class="dataTables-container">
        <table id="transfers-table" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>Date</th>
              <th>Reference</th>
              <th>Sender</th>
              <th>Receiver</th>
              <th>Amount (USD)</th>
              <th>From</th>
              <th>To</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentTransfers as $transfer)
              <tr>
                <td>{{ \Carbon\Carbon::parse($transfer->date_transfer)->format('M d, Y') }}</td>
                <td>
                  <span class="badge bg-light text-dark">
                    {{ $transfer->reference_code }}
                  </span>
                </td>
                <td>{{ $transfer->sender_name }}</td>
                <td>{{ $transfer->receiver_name }}</td>
                <td>
                  <strong>${{ number_format($transfer->amount, 2) }}</strong>
                </td>
                <td>{{ $transfer->ville_provenance }}</td>
                <td>{{ $transfer->ville_destination }}</td>
                <td>
                  <span class="status {{ strtolower($transfer->status) }} bn-success">
                    {{ ucfirst($transfer->status) }}
                  </span>
                </td>
                <td>
                  <div class="btn-group">
                    <a href="{{ route('transfers.edit', $transfer->id) }}" class="btn-edit"
                      style="background: none; border: none; color: var(--warning); cursor: pointer; margin-left: 8px; text-decoration: none;"
                      title="Edit">Edit
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('transfers.show', $transfer->id) }}" class="btn-view"
                      style="background: none; border: none; color: var(--primary); cursor: pointer; margin-left: 8px; text-decoration: none;"
                      title="View Details">Show
                      <i class="fas fa-eye"></i>
                    </a>

                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="no-data">
        <p>No transfers yet. <a href="{{ route('transfers.create') }}">Create one</a>.</p>
      </div>
    @endif
  </div>

  <!-- DataTables JavaScript -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function () {
      // Initialize DataTable
      var table = $('#transfers-table').DataTable({
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search transfers...",
          lengthMenu: "_MENU_ records per page",
          info: "Showing _START_ to _END_ of _TOTAL_ transfers",
          infoEmpty: "No transfers available",
          infoFiltered: "(filtered from _MAX_ total transfers)",
          paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
          }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        order: [[0, 'desc']], // Sort by date descending
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        initComplete: function () {
          // Add custom class to search input
          $('.dataTables_filter input').addClass('form-control');
          $('.dataTables_length select').addClass('form-select');
        }
      });

      // Refresh table button
      $('#refresh-table').on('click', function () {
        var $btn = $(this);
        var originalHtml = $btn.html();

        // Show loading spinner
        $btn.html('<span class="loading-spinner"></span> Refreshing...');
        $btn.prop('disabled', true);

        // Simulate refresh by reloading the page
        setTimeout(function () {
          location.reload();
        }, 1000);
      });

      // View button handler
      // Update the view button handler:
      $(document).on('click', '.btn-view', function () {
        var transferId = $(this).data('id');
        window.location.href = '/transfers/' + transferId;
      });

      // Edit button handler
      $(document).on('click', '.btn-edit', function () {
        var transferId = $(this).data('id');
        window.location.href = '/transfers/' + transferId + '/edit';
      });

      // Toast notification function
      function showToast(message, type = 'info') {
        // Remove existing toasts
        $('.custom-toast').remove();

        var bgColor = type === 'success' ? '#10b981' :
          type === 'warning' ? '#f59e0b' :
            type === 'error' ? '#ef4444' : '#2563eb';

        var toast = $(
          '<div class="custom-toast" style="position: fixed; top: 20px; right: 20px; background: ' + bgColor + '; color: white; padding: 12px 20px; border-radius: var(--radius); box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; animation: slideIn 0.3s ease;">' +
          '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>' +
          message +
          '</div>'
        );

        $('body').append(toast);

        setTimeout(function () {
          toast.fadeOut(300, function () {
            $(this).remove();
          });
        }, 3000);
      }

      console.log('DataTable initialized successfully');
    });

    // CSS for slide-in animation
    const style = document.createElement('style');
    style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
    document.head.appendChild(style);
  </script>
</body>

</html>