<!-- Update your resources/views/backend/components/header.blade.php -->
<!-- Add this enhanced notification section -->

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
            </ul>
        </div>
        <div class="dropdown-footer">
            <div class="row g-3">
                <div class="col-6">
                    <div class="d-grid">
                        <button class="btn btn-primary" onclick="markAllNotificationsRead()">Mark all read</button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-grid">
                        <a href="{{ route('client.chat') }}" class="btn btn-outline-secondary">View all messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let notificationsLoaded = false;
    
    // Load notifications when dropdown is opened
    document.querySelector('.dropdown-toggle[data-bs-toggle="dropdown"]').addEventListener('click', function() {
        if (!notificationsLoaded) {
            loadRecentNotifications();
            notificationsLoaded = true;
        }
    });
    
    // Function to load recent chat notifications
    function loadRecentNotifications() {
        const notificationsList = document.getElementById('notificationsList');
        const loadingIndicator = document.getElementById('loadingNotifications');
        
        // Get recent messages from all companies
        fetch('/api/client-messages/recent-notifications', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.remove();
            
            if (data.success && data.data.length > 0) {
                data.data.forEach(notification => {
                    const notificationItem = createNotificationItem(notification);
                    notificationsList.appendChild(notificationItem);
                });
            } else {
                notificationsList.innerHTML = `
                    <li class="list-group-item text-center">
                        <i class="ph-duotone ph-chat-circle f-24 text-muted"></i>
                        <p class="mb-0 mt-2 text-muted">No recent messages</p>
                    </li>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            loadingIndicator.innerHTML = `
                <div class="text-center text-danger">
                    <i class="ph-duotone ph-warning-circle f-18"></i>
                    <p class="mb-0 small">Error loading notifications</p>
                </div>
            `;
        });
    }
    
    function createNotificationItem(notification) {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        
        const timeAgo = formatTimeAgo(notification.sent_at);
        const isUnread = !notification.is_read;
        
        li.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <div class="avtar avtar-s ${notification.sender_type === 'admin' ? 'bg-light-primary' : 'bg-light-success'}">
                        <i class="ph-duotone ${notification.sender_type === 'admin' ? 'ph-user-gear' : 'ph-chat-circle'} f-18"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex">
                        <div class="flex-grow-1 me-3 position-relative">
                            <h6 class="mb-0 text-truncate ${isUnread ? 'fw-bold' : ''}">
                                ${notification.sender_type === 'admin' ? 'Admin Reply' : 'New Message'}
                                ${isUnread ? '<span class="badge bg-primary ms-1">New</span>' : ''}
                            </h6>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-sm">${timeAgo}</span>
                        </div>
                    </div>
                    <p class="position-relative mt-1 mb-2">
                        <span class="text-truncate d-block">
                            <strong>${notification.company_name}:</strong> 
                            ${notification.message || (notification.file_name ? `📎 ${notification.file_name}` : 'Sent a file')}
                        </span>
                    </p>
                    <a href="{{ route('client.chat') }}" class="btn btn-sm btn-outline-primary">
                        View Messages
                    </a>
                </div>
            </div>
        `;
        
        return li;
    }
    
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
        
        if (diffInMinutes < 1) {
            return 'Just now';
        } else if (diffInMinutes < 60) {
            return `${diffInMinutes}m ago`;
        } else if (diffInMinutes < 1440) {
            const hours = Math.floor(diffInMinutes / 60);
            return `${hours}h ago`;
        } else {
            const days = Math.floor(diffInMinutes / 1440);
            return `${days}d ago`;
        }
    }
    
   // Function to update notification badge
function updateNotificationBadge() {
    fetch('/client/chat/unread-counts')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                // Calculate total unread count
                let totalUnread = 0;
                for (const key in data.data) {
                    totalUnread += data.data[key];
                }
                
                // Update header badge
                const headerBadge = document.getElementById('headerNotificationBadge');
                if (headerBadge) {
                    if (totalUnread > 0) {
                        headerBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                        headerBadge.style.display = 'inline-block';
                    } else {
                        headerBadge.style.display = 'none';
                    }
                }
                
                // Update sidebar badge if exists
                const sidebarBadge = document.getElementById('unreadBadge');
                if (sidebarBadge) {
                    if (totalUnread > 0) {
                        sidebarBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                        sidebarBadge.classList.remove('d-none');
                    } else {
                        sidebarBadge.classList.add('d-none');
                    }
                }
            }
        })
        .catch(error => console.error('Error fetching unread counts:', error));
}
    
    // Update badge on page load and every 30 seconds
    updateNotificationBadge();
    setInterval(updateNotificationBadge, 30000);
    
    // Mark all notifications as read function (global)
    window.markAllNotificationsRead = function() {
        fetch('/api/client-messages/mark-all-read', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                client_email: '{{ Auth::guard('client')->user()->email }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationBadge();
                // Reload notifications
                notificationsLoaded = false;
                document.getElementById('notificationsList').innerHTML = `
                    <li class="list-group-item text-center" id="loadingNotifications">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <p class="mb-0 mt-2 text-muted small">Refreshing...</p>
                    </li>
                `;
                loadRecentNotifications();
            }
        })
        .catch(error => console.error('Error marking notifications as read:', error));
    };
});
</script>