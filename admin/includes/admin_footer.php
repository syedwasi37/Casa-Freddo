            </div><!-- /admin-content -->
        </div><!-- /admin-main -->
    </div><!-- /admin-wrapper -->
    
    <script>
        // Confirm delete actions
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>

