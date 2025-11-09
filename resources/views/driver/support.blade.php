@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <a href="{{ route('driver.dashboard') }}" class="btn btn-info px-4 py-2 rounded-pill">
                    <span data-key="return_to_dashboard">Return To Dashboard</span>
                </a>
                <div class="card-body text-center">
                    <h3 class="mb-3" data-key="support">Support</h3>

                    <p class="lead mb-4" data-key="support_contact_message">
                        If you need help or have any questions, please contact our support team:
                    </p>

                    <p class="fs-5">
                        <a href="mailto:transfertollc@gmail.com" class="text-decoration-none">
                            <i class="bi bi-envelope-fill me-2"></i>
                            transfertollc@gmail.com
                        </a>
                    </p>

                    <p class="text-muted mt-4"  data-key="support_help_message">
                        Our team will review your request as soon as possible. For urgent matters, 
                        please include in your email the vehicle number, order reference, or screenshots that help us understand the issue.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
