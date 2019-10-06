document.addEventListener("DOMContentLoaded", function() {
    const groupsDOM = document.getElementById("groups");

    document.getElementById("add-new-currency").addEventListener('click', function(e) {
        e.preventDefault();

        makeNewGroup(groupsDOM);
    });

    Array.prototype.forEach.call(document.getElementsByClassName("remove-group"), function(e) {
        e.addEventListener('click', function(e) {
            e.preventDefault();

            e.currentTarget.closest('div.row').remove();
        })
    });
});

function makeNewGroup(destination) {
    const paymentGroup = document.getElementById("init-group").cloneNode(true);

    paymentGroup.removeAttribute("id");
    paymentGroup.hidden = false;

    let groupNumber = destination.childElementCount;

    paymentGroup.querySelectorAll("input").forEach(function(e) {
        e.name = e.name + `[${groupNumber}]`;
    });

    destination.append(paymentGroup);
}
