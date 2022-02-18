(function () {
    var module_routes = [
    {
        "uri": "mailbox\/{id}\/signatures\/{signatureId}",
        "name": "mailbox.custom_signatures"
    }
];

    if (typeof(laroute) != "undefined") {
        laroute.add_routes(module_routes);
    } else {
        contole.log('laroute not initialized, can not add module routes:');
        contole.log(module_routes);
    }
})();