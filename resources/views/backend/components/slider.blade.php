<!-- Update your resources/views/backend/components/slider.blade.php -->

<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('client.dashboard') }}" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('frontend/images/logo/ritz_dark.svg') }}" alt="logo image" class="logo-lg"
                    width="200" />
            </a>
        </div>
        <div class="navbar-content">
            <ul>

                <li class="pc-item">
                    <a href="{{ route('client.dashboard') }}" class="pc-link">
                        <span class="pc-micon m-0">
                            <i class="ph-duotone ph-gauge"></i>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('client.profile') }}" class="pc-link">
                        <span class="pc-micon m-0">
                            <i class="ph-duotone ph-user-circle"></i>
                        </span>
                        <span class="pc-mtext">Profile</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('client.engagement') }}" class="pc-link">
                        <span class="pc-micon m-0">
                            <i class="ph-duotone ph-envelope"></i>
                        </span>
                        <span class="pc-mtext">Engagement Letter</span>
                    </a>
                </li>

                <!-- NEW: Chat/Messages Link -->
                <li class="pc-item">
                    <a href="{{ route('client.chat') }}" class="pc-link">
                        <span class="pc-micon m-0">
                            <i class="ph-duotone ph-chat-circle"></i>
                        </span>
                        <span class="pc-mtext">Messages</span>
                        <!-- Optional: Unread message badge -->
                        <span id="unreadBadge" class="badge bg-danger rounded-pill ms-2 d-none">0</span>
                    </a>
                </li>

            </ul>
            <div class="card nav-action-card bg-brand-color-4">
                <div class="card-body" style="background-image: url('../assets/images/layout/nav-card-bg.svg')">
                    <h5 class="text-dark">Help Center</h5>
                    <p class="text-dark text-opacity-75">Please contact us for more questions.</p>
                    <a href="https://phoenixcoded.support-hub.io/" class="btn btn-primary" target="_blank">Go to
                        help Center</a>
                </div>
            </div>
        </div>
        <div class="card pc-user-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('client')->user()->first_name . ' ' . Auth::guard('client')->user()->last_name) }}&background=random&color=fff&size=100"
                            alt="user-image" class="user-avtar wid-45 rounded-circle" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="dropdown">
                            <a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" data-bs-offset="0,20">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <h6 class="mb-0">{{ Auth::guard('client')->user()->full_name }}</h6>
                                        <small>Client</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="btn btn-icon btn-link-secondary avtar">
                                            <i class="ph-duotone ph-windows-logo"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li>
                                        <a href="{{ route('client.profile') }}" class="pc-user-links">
                                            <i class="ph-duotone ph-user"></i>
                                            <span>My Account</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('client.chat') }}" class="pc-user-links">
                                            <i class="ph-duotone ph-chat-circle"></i>
                                            <span>Messages</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="pc-user-links">
                                            <i class="ph-duotone ph-gear"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('client.logout') }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="pc-user-links w-100 border-0 bg-transparent text-start">
                                                <i class="ph-duotone ph-power"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->

<script>
// Optional: Add unread message count to navigation
document.addEventListener('DOMContentLoaded', function() {
    // Function to update unread badge
    function updateUnreadBadge() {
        fetch('/client/chat/unread-counts')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const totalUnread = Object.values(data.data).reduce((sum, count) => sum + count, 0);
                    const badge = document.getElementById('unreadBadge');
                    
                    if (totalUnread > 0) {
                        badge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                }
            })
            .catch(error => console.error('Error fetching unread counts:', error));
    }
    
    // Update badge on page load
    updateUnreadBadge();
    
    // Update badge every 30 seconds
    setInterval(updateUnreadBadge, 30000);
});
</script>