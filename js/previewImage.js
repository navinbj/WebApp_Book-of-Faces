/**
 * Created by dylan on 14/01/17.
 */

function confirmDelete()
{
    return confirm("Are you sure you want to delete this user?");

}



function previewImage(image) {
    if (image.files && image.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        };

        reader.readAsDataURL(image.files[0]);
    }
}

function showDetails(profile, a1, a2, a3, a4, a5, image) {
    if (profile.innerHTML.toString().includes("<img")) {
        profile.innerHTML = "<div class='interests' style='background-color: #ffffff; height: 100%;" +
            "border: solid; border-width: 1px; width: 100%; display: table; padding: 10px'>" +
            "<div class='interestAnswers' style='display: table-cell; vertical-align: middle'>" +
            "<p class='interestQuestion' style='color: #D7342B; font-size: 16px; margin-bottom: 0; font-weight: bold'>The last thing that i've Googled is: </p>" +
            "<p class='interestAnswer' style='margin-bottom: 20px; font-size: 14px'>" + a1 + "</p>" +
            "<p class='interestQuestion' style='color: #D7342B; font-size: 16px; margin-bottom: 0; font-weight: bold'>I would like to be the best in: </p>" +
            "<p class='interestAnswer' style='margin-bottom: 20px; font-size: 14px'>" + a2 + "</p>" +
            "<p class='interestQuestion' style='color: #D7342B; font-size: 16px; margin-bottom: 0; font-weight: bold'>My favourite game, film or series is: </p>" +
            "<p class='interestAnswer' style='margin-bottom: 20px; font-size: 14px'>" + a3 + "</p>" +
            "<p class='interestQuestion' style='color: #D7342B; font-size: 16px; margin-bottom: 0; font-weight: bold'>My biggest failure i'd like to share is: </p>" +
            "<p class='interestAnswer' style='margin-bottom: 20px; font-size: 14px'>" + a4 + "</p>" +
            "<p class='interestQuestion' style='color: #D7342B; font-size: 16px; margin-bottom: 0; font-weight: bold'>If I could be any person I would be: </p>" +
            "<p class='interestAnswer' style='margin-bottom: 20px; font-size: 14px'>" + a5 + "</p>" +
            "</div>" +
            "</div>";
    }
    else {
        profile.innerHTML = "<img class='img' src='"+ image +"' alt='profile picture'>"
    }
}

function confirmDelete() {
    return confirm("Are you sure you want to delete this profile?");
}