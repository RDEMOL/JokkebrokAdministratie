define(["pinger"], function (Pinger) {
    var p = new Pinger("?action=ping", function () {
        window.location = "index.php";
    });
    p.start();
});
