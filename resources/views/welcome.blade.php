<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Simple Donation with Midtrans</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <style>
        html, body {
        background-color: #fff;
        color: #636b6f;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Online Donation</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#create">Donation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#list">Donation List</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="jumbotron jumbotron-fluid" style="background-color: #74b9ff; color: white;">
        <div class="container">
            <h1 class="display-4">Online Donation</h1>
            <p class="lead">This is just simple donation web with Midtrans.</p>
        </div>
    </div>

    <div class="container">

        <form class="form-horizontal" id="donation" onsubmit="return submitForm();">

            <!-- Form Name -->
            <legend>Donation</legend>

            <div class="row">
                <div class="col-md-4">

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="control-label" for="donor_name">Donor Name</label>
                        <div>
                            <input id="donor_name" name="donor_name" type="text" placeholder="Enter your name.." class="form-control input-md"
                                required="">

                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="control-label" for="donor_email">Donor Email</label>
                        <div>
                            <input id="donor_email" name="donor_email" type="text" placeholder="Enter your email.." class="form-control input-md"
                                required="">
    
                        </div>
                    </div>
    
                </div>

                <div class="col-md-4">

                    <!-- Select Basic -->
                    <div class="form-group">
                        <label class="control-label" for="donation_type">Type</label>
                        <div>
                            <select id="donation_type" name="donation_type" class="form-control">
                                <option value="infak_kemanusiaan">Infak Kemanusiaan</option>
                                <option value="infak_pendidikan">Infak Pendidikan</option>
                                <option value="infak_kesehatan">Infak Kesehatan</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">

                    <!-- Prepended text-->
                    <label for="">Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                        </div>
                        <input id="amount" name="amount" class="form-control" placeholder="" type="number" min="10000" max="999999999" required="">
                    </div>

                </div>
                <div class="col-md-6">

                    <!-- Textarea -->
                    <div class="form-group">
                        <label class="control-label" for="note">Note (Optional)</label>
                        <div>
                            <textarea class="form-control" id="note" name="note"></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <button id="submit" class="btn btn-success">Submit</button>

        </form>

        <br>

        <table class="table table-striped" id="list">
            <tr>
                <th>ID</th>
                <th>Donor Name</th>
                <th>Amount</th>
                <th>Donation Type</th>
                <th>Status</th>
                <th style="text-align: center;">Pay</th>
            </tr>
            @foreach ($donations as $donation)
            <tr>
                <td><code>{{ $donation->id }}</code></td>
                <td>{{ $donation->donor_name }}</td>
                <td>Rp. {{ number_format($donation->amount) }},-</td>
                <td>{{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}</td>
                <td>{{ ucfirst($donation->status) }}</td>
                <td style="text-align: center;">
                    @if ($donation->status == 'pending')
                    <button class="btn btn-success btn-sm" onclick="snap.pay('{{ $donation->snap_token }}')">Complete Payment</button>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">{{ $donations->links() }}</td>
            </tr>
        </table>

    </div>
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="{{ !config('services.midtrans.isProduction') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script>
    function submitForm() {
        $.post("{{ route('donation.store') }}",
        {
            _method: 'POST',
            _token: '{{ csrf_token() }}',
            amount: $('input#amount').val(),
            note: $('textarea#note').val(),
            donation_type: $('select#donation_type').val(),
            donor_name: $('input#donor_name').val(),
            donor_email: $('input#donor_email').val(),
        },
        function (data, status) {
            if (data.status == 'error') {
                alert(data.message);
            } else {
                snap.pay(data.snap_token, {
                    // Optional
                    onSuccess: function (result) {
                        location.reload();
                    },
                    // Optional
                    onPending: function (result) {
                        location.reload();
                    },
                    // Optional
                    onError: function (result) {
                        location.reload();
                    }
                });
            }
        });
        return false;
    }
    </script>
</body>
</html>
