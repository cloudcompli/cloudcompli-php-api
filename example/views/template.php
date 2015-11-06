<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CloudCompli API v2 Example</title>
    </head>
    <body>
        <button style="position:fixed; top: 0; right: 0; background-color: #39f; color: #fff; font-weight: bold; z-index: 1000000; padding: 10px; margin: 5px 5px 0 0;" id="reload-page">Restart Flow</button>
        <script type="text/javascript">
            document.getElementById('reload-page').addEventListener('click', function(){
                window.location = '//' + window.location.host + window.location.pathname;
            })
        </script>
        <?php echo $content; ?>
    </body>
</html>