$("#adminlisttickets button.switchmytickets").click(function(){
    $("#adminlisttickets .mytickets").removeClass("d-none");
    $("#adminlisttickets .othertickets").addClass("d-none");
});

$("#adminlisttickets button.switchothertickets").click(function(){
    $("#adminlisttickets .othertickets").removeClass("d-none");
    $("#adminlisttickets .mytickets").addClass("d-none");
});

$("input.thefile").change(function(){
    function readURL(input) {
        if (input.files && input.files[0]) {
            //lire le contenu de fichiers de façon asynchrone
            var reader = new FileReader();
            reader.fileName = input.files[0].name
            reader.onload = function (e) {
                $(".showfilename").html(e.target.fileName);
            }
            //démarrer la lecture du contenu pour le blob indiqué
            reader.readAsDataURL(input.files[0]);
        }
    }
    readURL(this);
});