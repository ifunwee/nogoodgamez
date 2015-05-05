/**
 * jTinder initialization
 */
$("#tinderslide").jTinder({
	// dislike callback
    onDislike: function () {

        var gamenumber = $("#gamelist li").length;
        var gamename = $.trim($("#gamelist li:last").text());

        $.ajax({
            url: 'liker.php',//abs path
            type: 'post',
            data: {'action': 'dislike', 'gamename':gamename, 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').append(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
        $("#tinderslide").jTinder(options);

        //init view with 'pane'.(item.index()+2) class
    },
	// like callback
    onLike: function () {

        var gamenumber = $("#gamelist li").length;
        var gamename = $.trim($("#gamelist li:last").text());

        $.ajax({
            url: 'liker.php',
            type: 'post',
            data: {'action': 'like', 'gamename': gamename, 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').append(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
        $("#tinderslide").jTinder(options);
    },
	animationRevertSpeed: 200,
	animationSpeed: 400,
	threshold: 35,
	likeSelector: '.like',
	dislikeSelector: '.dislike'
});

/**
 * Set button action to trigger jTinder like & dislike.
 */
$('.actions .like, .actions .dislike').click(function(e){
	e.preventDefault();
	$("#tinderslide").jTinder($(this).attr('class'));
});