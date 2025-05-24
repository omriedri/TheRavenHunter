<scripts>
    <script> window.GOOGLE_CLIENT_ID = "<?= $_ENV['GOOGLE_CLIENT_ID'] ?? '' ?>"; </script>
    <script type="module" src="<?= PathHelper::module('Main.js') ?>"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</scripts>