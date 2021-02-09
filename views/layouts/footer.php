<script>
    <?php if (!empty($this->some_notification)) { ?>
        Swal.fire({
            icon: '<?php echo $this->some_notification['type']; ?>',
            title: '<?php echo $this->some_notification['title']; ?>',
            text: '<?php echo $this->some_notification['text']; ?>',
            confirmButtonText: '<?php echo $this->some_notification['button_text']; ?>',
            confirmButtonColor: '#3b3b3b',
        });
    <?php } ?>
</script>
</body>

</html>