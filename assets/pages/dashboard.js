import '../app';
import "../styles/pages/dashboard.css";
import "../scripts/datatables";

import { showLoader, hideLoader } from "../scripts/utils";


const getUsers = () => {
    showLoader()

    $('#table').DataTable({
        ajax: '/dashboard/get-users',
        paging: false,
        columns: [
            { title: "ID" },
            { title: "Firstname" },
            { title: "Lastname" },
            { title: "Email" },
        ],
        initComplete: function (settings, json) {
            hideLoader();
        }
    });
}

const vUserBtn = document.querySelector('#viewUsers');
vUserBtn.addEventListener('click', getUsers);
