function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function openVertTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("vert_tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("vert_tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");

    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}


function roleSelector(val, num) {
    var i, roleContent;
    // alert(val);
    roleContent = document.getElementsByClassName('insertPerson_role' + num);
    for (i = 0; i < roleContent.length; i++) {
        // alert(roleContent);
        roleContent[i].style.display = "none";
    }

    document.getElementById(val).style.display = "block";
}

function bioSelector(toHide, target) {
    var bioContent, i, j, current, children;
    bioContent = document.getElementsByClassName(toHide);
    for (i = 0; i < bioContent.length; i++) {
        bioContent[i].style.display = 'none';
        // children = bioContent[i].getElementsByTagName('input');
        // for (j = 0; j < children.length; j++) {
        //     children[j].removeAttribute('required');
        // }
    }

    current = document.getElementById('bioinfo_' + target);
    current.style.display = 'block';
    // children = current.getElementsByTagName('input');
    //     for (j = 0; j < children.length; j++) {
    //         children[j].required = true;
    //     }
}

function setDefaultBio(){
    document.getElementById("form_bioSelect").selectedIndex = 0;
}