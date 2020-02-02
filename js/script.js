$(function() {
    $(".linkmodal.participationPicture").click(function() {
        $("#modal_participant_name").text($(this).attr('data-name'))
        $("#modal_image_participation").attr('src', $(this).attr('data-image'));
        if ($(this).attr('data-text').length != 0) {
            $("#modal_desc_participation").text($(this).attr('data-text')).show();
        } else {
            $("#modal_desc_participation").hide();
        }
    });
    $(".concoursAvenir").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Le concours n\'a pas encore débuté.<br>(EN) The contest has not started yet.</div>'
    });
    $(".concoursEnCours").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Le concours est disponible, vous pouvez participer.<br>(EN) The contest is open, you can submit your participation.</div>'
    });
    $(".voteEnCours").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Le concours est terminé, les votes sont en cours.<br>(EN) The contest is over, jury votes in progress.</div>'
    });
    $(".concoursTermine").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Le concours est terminé, découvrez le podium de cette édition.<br>(EN) The contest is over, discover the podium of this edition.</div>'
    });
    $(".dofusPc").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Ce concours est lié au jeu <strong>DOFUS PC</strong> uniquement.<br>(EN) This contest is linked to <strong>DOFUS PC</strong> game only.</div>'
    });
    $(".dofusTouch").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Ce concours est lié au jeu <strong>DOFUS TOUCH</strong> uniquement.<br>(EN) This contest is linked to <strong>DOFUS TOUCH</strong> game only.</div>'
    });
    $(".dofusBoth").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Ce concours est lié aux jeux <strong>DOFUS PC et DOFUS TOUCH</strong>, les cadeaux seront crédités sur le jeu de votre choix.<br>(EN) This contest is linked to <strong>DOFUS & DOFUS TOUCH</strong> games, the gifts will be credited on the game of your choice.</div>'
    });
    $(".goParticipate").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Participer au concours.<br>(EN) Participate in this contest.</div>'
    });
    $(".goSee").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Voir les participations des joueurs.<br>(EN) See the player\'s participations.</div>'
    });
    $(".goConnected").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">(FR) Tu dois être connecté pour participer. Clique sur "Connexion" en haut du site.<br>(EN) You must be logged in to participate. Click on "Connexion" at the top of the site.</div>'
    });
});

var reader = new FileReader();
reader.onload = function(e) {
    $('#showImage').html('<img src="' + e.target.result + '" class="img-thumbnail" width="250px"/>');
}

function readURL(input) {
    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).on('change', "input#image_p", function() {
    readURL(this);
});