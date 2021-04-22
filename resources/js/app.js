import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function () {
    $(".nav-toggler").each(function (_, navToggler) {
        var target = $(navToggler).data("target");
        $(navToggler).on("click", function () {
            $(target).animate({
                height: "toggle"
            });
        });
    });

    $(".player").click(function() {
        let playerId = $(this).attr("id");
        $( "#scoreCard"+playerId).toggle();

    });
});
