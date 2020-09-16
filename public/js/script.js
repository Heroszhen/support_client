$("#adminlisttickets button.switchmytickets").click(function(){
    $("#adminlisttickets .mytickets").removeClass("d-none");
    $("#adminlisttickets .othertickets").addClass("d-none");
});

$("#adminlisttickets button.switchothertickets").click(function(){
    $("#adminlisttickets .othertickets").removeClass("d-none");
    $("#adminlisttickets .mytickets").addClass("d-none");
});