$(function() {
    $('.fin_concours').datetimepicker({
        language: 'fr-FR'
    });
    $('.debut_concours').datetimepicker({
        language: 'fr-FR'
    });
    $(".btnEdit").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">Éditer les paramètres du concours.</div>'
    });
    $(".btnVote").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">Noter les participations</div>'
    });
    $(".btnDelete").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">Supprimer le concours</div>'
    });
    $(".btnSee").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">Voir/Gérer les participations</div>'
    });
    $(".btnResult").popover({
        placement: 'bottom',
        trigger: 'hover',
        html: true,
        container: 'body',
        content: '<div class="text-center">Voir les résultats</div>'
    });

    $(".btnDelete").on('click', function() {
        var concours = $(this).attr('data-concours');
        var nameConcours = $(this).attr('data-name');
        $("#selectConcours").val(concours);
        $("#titreconcours").text(nameConcours);
        $("#dialogdelConcours").dialog('open');
    });

    $(".linkmodal").on('click', function() {
        var authorName = $(this).attr('data-name');
        var imageSrc = $(this).attr('data-image');
        var imageDesc = $(this).attr('data-text');
        var participationId = $(this).attr('data-id');

        $("#selectParticipation").val(participationId);
        $("#imageSrc").attr('src', imageSrc);
        $("#userName").text(authorName);
        $("blockquote#imageDesc").text(imageDesc);
        $("#dialogdelParticipation").dialog('open');
    });

    $(".select_vote").on('change', function() {
        var participationId = $(this).attr('data-id');
        var idJol = $("#votantId").val();
        var idConcours = $("#concoursId").val();
        var selectedNote = parseFloat($(this).find(':selected').val());
        $.post("https://dofus.jeuxonline.info/challenge-jolia/admin/addVote", { ParticipationId: participationId, VotantId: idJol, NoteValue: selectedNote, Concours: idConcours }, function(data) {
            if (data == 'ok') {
                $("#icon_" + participationId).attr('class', 'jol-icon jol-icon-check').attr('style', 'color:#07981B');
            }
            if (data == 'updateOK') {
                $("#icon_" + participationId).attr('class', 'jol-icon jol-icon-check').attr('style', 'color:#0785CE');
            }
            if (data == 'noParticipation') {
                $("#icon_" + participationId).attr('class', 'jol-icon jol-icon-close').attr('style', 'color:#CE0735');
            }
            if (data == 'erreur') {
                $("#icon_" + participationId).attr('class', 'jol-icon jol-icon-close').attr('style', 'color:#CE0735');
            }
            console.log(selectedNote);
        });
    });
});
$(document).ready(function() {
    function delConcours() {
        var selectConcours = $("#selectConcours").val();
        if (selectConcours == '') {
            alert('Veuillez choisir un Concours');
        } else {
            $.post("https://dofus.jeuxonline.info/challenge-jolia/admin/delConcours", { Concours: selectConcours }, function(data) {
                if (data == 'ok') {
                    alert("Le concours a été supprimé et toutes les participations et votes liés le sont également.");
                    $("#concours_" + selectConcours).hide("slow");
                    $("#selectConcours").val('');
                    $(this).dialog('close');
                }
                if (data == 'noConcours') {
                    alert("Le concours que vous souhaitez supprimer n'existe pas ou plus.");
                    $("#selectConcours").val('');
                    $(this).dialog('close');
                }
                if (data == 'erreur') {
                    alert("Une erreur est survenue, contactez l'administration pour en savoir plus !");
                }
            });
        }
    }

    function delParticipation() {
        var selectParticipation = $("#selectParticipation").val();
        if (selectParticipation == '') {
            alert('Veuillez choisir un Concours');
        } else {
            $.post("https://dofus.jeuxonline.info/challenge-jolia/admin/delParticipation", { Participation: selectParticipation }, function(data) {
                if (data == 'ok') {
                    alert("La participation sélectionnée a été supprimée, les éventuels votes associés également.");
                    $("#participation_" + selectParticipation).hide("slow");
                    $("#selectParticipation").val('');
                    $(this).dialog('close');
                }
                if (data == 'noParticipation') {
                    alert("La participation que vous souhaitez supprimer n'existe pas ou plus.");
                    $("#selectParticipation").val('');
                    $(this).dialog('close');
                }
                if (data == 'erreur') {
                    alert("Une erreur est survenue, contactez l'administration pour en savoir plus !");
                }
            });
        }
    }

    $("#dialogdelConcours").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        closeOnEscape: false,
        draggable: false,
        width: 'auto',
        buttons: [{
                text: "Annuler",
                click: function() {
                    $("#selectConcours").val('');
                    $(this).dialog('close');
                }
            },
            {
                text: "Supprimer",
                click: function() {
                    delConcours();
                    $(this).dialog('close');
                }
            }
        ],
        open: function() {
            $('.ui-dialog-buttonpane').find('button:contains("Annuler")').removeClass('ui-button').addClass('btn btn-warning');
            $('.ui-dialog-buttonpane').find('button:contains("Supprimer")').removeClass('ui-button').addClass('btn btn-danger');
        }
    });

    $("#dialogdelParticipation").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        closeOnEscape: false,
        draggable: false,
        width: 'auto',
        buttons: [{
                text: "Annuler",
                click: function() {
                    $("#selectParticipation").val('');
                    $(this).dialog('close');
                }
            },
            {
                text: "Supprimer la participation",
                click: function() {
                    delParticipation();
                    $(this).dialog('close');
                }
            }
        ],
        open: function() {
            $('.ui-dialog-buttonpane').find('button:contains("Annuler")').removeClass('ui-button').addClass('btn btn-warning');
            $('.ui-dialog-buttonpane').find('button:contains("Supprimer")').removeClass('ui-button').addClass('btn btn-danger');
        }
    });
});