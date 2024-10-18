<!-- footer.php -->
<script>
    window.onload = () => {
        const toastData = <?php echo getToast(); ?>; // Call the PHP function to get toast data
        if (toastData) {
            showToast(toastData.message, toastData.type);
        }
    };
</script>
</main>
<footer>
    <!-- <p>&copy; <?php echo date('Y'); ?> My PHP App</p> -->
</footer>

</body>

</html>