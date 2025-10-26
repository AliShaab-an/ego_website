


    
    <div class="admin-content ml-64 p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Contact Messages</h1>
                <div class="flex space-x-4">
                    <button id="allBtn" class="filter-btn active px-4 py-2 rounded-lg bg-brand text-white">
                        All (<span id="totalCount">0</span>)
                    </button>
                    <button id="unreadBtn" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Unread (<span id="unreadCount">0</span>)
                    </button>
                    <button id="readBtn" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Read (<span id="readCount">0</span>)
                    </button>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messagesContainer" class="space-y-4">
                <div id="loadingSpinner" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-brand"></div>
                    <p class="mt-2 text-gray-600">Loading messages...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="mt-6 flex justify-center items-center space-x-4 hidden">
                <button id="prevBtn" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <span id="pageInfo" class="text-gray-600"></span>
                <button id="nextBtn" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Message Details Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Message Details</h2>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script>
        let currentPage = 1;
        let currentFilter = 'all';
        const limit = 10;

        $(document).ready(function() {
            loadMessages();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Filter buttons
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active bg-brand text-white').addClass('bg-gray-200 text-gray-700');
                $(this).removeClass('bg-gray-200 text-gray-700').addClass('active bg-brand text-white');
                
                currentFilter = $(this).attr('id').replace('Btn', '');
                currentPage = 1;
                loadMessages();
            });

            // Pagination
            $('#prevBtn').click(function() {
                if (currentPage > 1) {
                    currentPage--;
                    loadMessages();
                }
            });

            $('#nextBtn').click(function() {
                currentPage++;
                loadMessages();
            });

            // Modal close
            $('#closeModal, #messageModal').click(function(e) {
                if (e.target === this) {
                    $('#messageModal').addClass('hidden');
                }
            });
        }

        function loadMessages() {
            $('#loadingSpinner').show();
            $('#messagesContainer .message-item').remove();
            $('#paginationContainer').addClass('hidden');

            $.ajax({
                url: 'api/list-contact-messages.php',
                method: 'GET',
                data: {
                    page: currentPage,
                    limit: limit,
                    status: currentFilter
                },
                success: function(response) {
                    $('#loadingSpinner').hide();
                    
                    if (response.success) {
                        displayMessages(response.data.messages);
                        updateCounts(response.data.counts);
                        updatePagination(response.data.pagination);
                    } else {
                        showError('Failed to load messages: ' + response.message);
                    }
                },
                error: function() {
                    $('#loadingSpinner').hide();
                    showError('Failed to load messages. Please try again.');
                }
            });
        }

        function displayMessages(messages) {
            const container = $('#messagesContainer');
            
            if (messages.length === 0) {
                container.append(`
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>No messages found.</p>
                    </div>
                `);
                return;
            }

            messages.forEach(function(message) {
                const isUnread = message.is_read == 0;
                const messageHtml = `
                    <div class="message-item p-4 border rounded-lg hover:bg-gray-50 cursor-pointer ${isUnread ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-200'}" 
                         data-id="${message.id}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="font-semibold text-gray-800">${escapeHtml(message.name)}</h3>
                                    <span class="text-sm text-gray-500">${escapeHtml(message.email)}</span>
                                    ${isUnread ? '<span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">New</span>' : ''}
                                    ${message.username ? `<span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">User</span>` : ''}
                                </div>
                                <p class="text-gray-600 truncate">${escapeHtml(message.message.substring(0, 100))}${message.message.length > 100 ? '...' : ''}</p>
                                <p class="text-sm text-gray-500 mt-2">${formatDate(message.created_at)}</p>
                            </div>
                            <div class="flex space-x-2">
                                ${isUnread ? `<button class="mark-read-btn text-blue-600 hover:text-blue-800" data-id="${message.id}" title="Mark as read">
                                    <i class="fas fa-eye"></i>
                                </button>` : ''}
                                <button class="view-btn text-gray-600 hover:text-gray-800" data-id="${message.id}" title="View details">
                                    <i class="fas fa-expand-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.append(messageHtml);
            });

            // Event listeners for message actions
            $('.message-item').click(function(e) {
                if (!$(e.target).closest('button').length) {
                    const messageId = $(this).data('id');
                    viewMessage(messageId);
                }
            });

            $('.mark-read-btn').click(function(e) {
                e.stopPropagation();
                const messageId = $(this).data('id');
                markAsRead(messageId);
            });

            $('.view-btn').click(function(e) {
                e.stopPropagation();
                const messageId = $(this).data('id');
                viewMessage(messageId);
            });
        }

        function updateCounts(counts) {
            $('#totalCount').text(counts.total);
            $('#unreadCount').text(counts.unread);
            $('#readCount').text(counts.read);
        }

        function updatePagination(pagination) {
            if (pagination.total_pages > 1) {
                $('#paginationContainer').removeClass('hidden');
                $('#pageInfo').text(`Page ${pagination.current_page} of ${pagination.total_pages} (${pagination.total_count} total)`);
                
                $('#prevBtn').prop('disabled', !pagination.has_prev);
                $('#nextBtn').prop('disabled', !pagination.has_next);
            }
        }

        function markAsRead(messageId) {
            $.ajax({
                url: 'api/mark-contact-read.php',
                method: 'POST',
                data: { id: messageId },
                success: function(response) {
                    if (response.success) {
                        loadMessages(); // Reload to update counts and styling
                    } else {
                        showError('Failed to mark message as read: ' + response.message);
                    }
                },
                error: function() {
                    showError('Failed to mark message as read. Please try again.');
                }
            });
        }

        function viewMessage(messageId) {
            // For now, we'll just show an alert. In a real implementation, you'd load the full message
            $.ajax({
                url: 'api/get-contact-message.php',
                method: 'GET',
                data: { id: messageId },
                success: function(response) {
                    if (response.success) {
                        const message = response.data;
                        const modalContent = `
                            <div class="space-y-4">
                                <div class="border-b pb-4">
                                    <h3 class="font-semibold text-lg">${escapeHtml(message.name)}</h3>
                                    <p class="text-gray-600">${escapeHtml(message.email)}</p>
                                    <p class="text-sm text-gray-500">${formatDate(message.created_at)}</p>
                                    ${message.username ? `<span class="inline-block bg-green-500 text-white px-2 py-1 rounded-full text-xs mt-2">Registered User: ${escapeHtml(message.username)}</span>` : ''}
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">Message:</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="whitespace-pre-wrap">${escapeHtml(message.message)}</p>
                                    </div>
                                </div>
                                ${message.is_read == 0 ? `
                                    <div class="flex justify-end">
                                        <button id="markReadModal" class="bg-brand text-white px-4 py-2 rounded-lg hover:bg-opacity-90" data-id="${message.id}">
                                            Mark as Read
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                        
                        $('#modalContent').html(modalContent);
                        $('#messageModal').removeClass('hidden');
                        
                        // Mark as read button in modal
                        $('#markReadModal').click(function() {
                            markAsRead($(this).data('id'));
                            $('#messageModal').addClass('hidden');
                        });
                    }
                },
                error: function() {
                    showError('Failed to load message details.');
                }
            });
        }

        function showError(message) {
            alert(message); // Simple alert for now
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }
    </script>
</body>
</html>