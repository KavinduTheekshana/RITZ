<!-- resources/views/backend/components/header.blade.php -->
<!-- [ Header Topbar ] start -->
<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- [ Mobile Menu ] start -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <!-- [ Mobile Menu ] end -->
            </ul>
        </div>
        
        <!-- [ Header Right ] start -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <!-- Notifications Dropdown -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ph-duotone ph-bell"></i>
                        <span class="badge bg-success pc-h-badge" id="headerNotificationBadge">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifications</h5>
                            <ul class="list-inline ms-auto mb-0">
                                <li class="list-inline-item">
                                    <a href="{{ route('client.chat') }}" class="avtar avtar-s btn-link-hover-primary">
                                        <i class="ph-duotone ph-chat-circle f-18"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative"
                            style="max-height: calc(100vh - 235px)">
                            <ul class="list-group list-group-flush" id="notificationsList">
                                <!-- Chat notifications will be loaded here -->
                                <li class="list-group-item text-center" id="loadingNotifications">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mb-0 mt-2 text-muted small">Loading notifications...</p>
                                </li>
                                <li class="list-group-item text-center d-none" id="noNotifications">
                                    <p class="mb-0 text-muted">No new notifications</p>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown-footer">
                            <div class="text-center py-2">
                                <a href="{{ route('client.chat') }}" class="link-primary">View all messages</a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- User Profile Dropdown -->
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('client')->user()->first_name . ' ' . Auth::guard('client')->user()->last_name) }}&background=random&color=fff&size=100" 
                            alt="user-image" class="user-avtar" />
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Profile</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                <ul class="list-group list-group-flush w-100">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('client')->user()->first_name . ' ' . Auth::guard('client')->user()->last_name) }}&background=random&color=fff&size=100"
                                                    alt="user-image" class="wid-50 rounded-circle" />
                                            </div>
                                            <div class="flex-grow-1 mx-3">
                                                <h5 class="mb-0">{{ Auth::guard('client')->user()->full_name }}</h5>
                                                <span class="text-muted">{{ Auth::guard('client')->user()->email }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('client.profile') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-user-circle"></i>
                                                <span>My Profile</span>
                                            </span>
                                        </a>
                                        <a href="{{ route('client.chat') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-chat-circle"></i>
                                                <span>Messages</span>
                                                <span class="badge bg-light-success ms-auto" id="headerMessageBadge"></span>
                                            </span>
                                        </a>
                                        <a href="{{ route('client.engagement') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-envelope"></i>
                                                <span>Engagement Letters</span>
                                            </span>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-gear"></i>
                                                <span>Settings</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <form method="POST" action="{{ route('client.logout') }}" class="mb-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-power"></i>
                                                    <span>Logout</span>
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- [ Header Right ] end -->
    </div>
</header>
<!-- [ Header ] end -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to load notifications
    function loadNotifications() {
        const clientEmail = '{{ Auth::guard("client")->user()->email }}';
        
        fetch(`/api/client-messages/recent-notifications?client_email=${encodeURIComponent(clientEmail)}&limit=5`)
            .then(response => response.json())
            .then(data => {
                const notificationsList = document.getElementById('notificationsList');
                const loadingElement = document.getElementById('loadingNotifications');
                const noNotificationsElement = document.getElementById('noNotifications');
                const headerBadge = document.getElementById('headerNotificationBadge');
                
                // Clear loading state
                loadingElement.classList.add('d-none');
                
                if (data.success && data.data.length > 0) {
                    // Clear existing notifications
                    notificationsList.innerHTML = '';
                    
                    // Count unread messages
                    let unreadCount = 0;
                    
                    // Add notifications
                    data.data.forEach(notification => {
                        if (!notification.is_read) {
                            unreadCount++;
                        }
                        
                        const notificationItem = document.createElement('li');
                        notificationItem.className = 'list-group-item';
                        
                        const isUnread = !notification.is_read ? 'fw-bold' : '';
                        const timeAgo = getTimeAgo(new Date(notification.sent_at));
                        
                        notificationItem.innerHTML = `
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-s ${notification.sender_type === 'admin' ? 'bg-light-primary' : 'bg-light-success'}">
                                        <i class="ph-duotone ${notification.sender_type === 'admin' ? 'ph-user-circle' : 'ph-user'} f-18"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex align-items-start justify-content-between mb-1">
                                        <h6 class="mb-0 ${isUnread}">${notification.company_name}</h6>
                                        <small class="text-muted">${timeAgo}</small>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="${isUnread}">${notification.sender_name}:</span>
                                        ${notification.message || (notification.file_name ? 'Sent a file' : 'New message')}
                                    </p>
                                </div>
                            </div>
                        `;
                        
                        notificationsList.appendChild(notificationItem);
                    });
                    
                    // Update badge
                    if (unreadCount > 0) {
                        headerBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        headerBadge.classList.remove('d-none');
                    } else {
                        headerBadge.classList.add('d-none');
                    }
                } else {
                    noNotificationsElement.classList.remove('d-none');
                    headerBadge.classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('loadingNotifications').classList.add('d-none');
                document.getElementById('noNotifications').classList.remove('d-none');
            });
    }
    
    // Function to get relative time
    function getTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        
        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + ' years ago';
        
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + ' months ago';
        
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + ' days ago';
        
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + ' hours ago';
        
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + ' minutes ago';
        
        return 'Just now';
    }
    
    // Load notifications on page load
    loadNotifications();
    
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Update message badge in profile dropdown
    function updateMessageBadge() {
        fetch(`/client/chat/unread-counts`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const totalUnread = Object.values(data.data).reduce((sum, count) => sum + count, 0);
                    const badge = document.getElementById('headerMessageBadge');
                    
                    if (badge && totalUnread > 0) {
                        badge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                        badge.classList.remove('d-none');
                    } else if (badge) {
                        badge.classList.add('d-none');
                    }
                }
            })
            .catch(error => console.error('Error updating message badge:', error));
    }
    
    // Update message badge on load
    updateMessageBadge();
});
</script>