import * as $ from 'jquery';
import Swal from 'sweetalert2';

export default (function () {
    $(document).on('click', "form.delete button", function(e) {
        var _this = $(this);
        e.preventDefault();
        Swal.fire({
            title: 'Προσοχή', // Opération Dangereuse
            text: $(this).data( "msg" ), // Êtes-vous sûr de continuer ?
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: 'null',
            cancelButtonColor: 'null',
            confirmButtonClass: 'btn btn-danger sweet-btn-delete',
            cancelButtonClass: 'btn btn-primary',
            confirmButtonText: 'Διαγραφή', // Oui, sûr
            cancelButtonText: 'Άκυρο', // Annuler
        }).then(res => {
            if (res.value) {
                _this.closest("form").submit();
            }
        });
    });
}())
