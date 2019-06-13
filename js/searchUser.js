function showHint(str) {
    console.log(str.length);
    if (str.length == 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if (this.readyState == 4 && this.status == 200)
            {

                document.getElementById("sugar").innerHTML = this.responseText;

            }

        };
        xmlhttp.open("GET", "indextest.php", true);
        xmlhttp.send();

    }
    else
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if (this.readyState == 4 && this.status == 200)
            {

                document.getElementById("sugar").innerHTML = this.responseText;

            }

        };
        xmlhttp.open("GET", "indextest.php?search=" + str, true);
        xmlhttp.send();

    }

}
