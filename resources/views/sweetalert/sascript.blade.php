<script>
    function loader(){
        Swal.fire({
                    title: "Loading",
                    imageUrl: '{{ asset('loader/Loader.gif') }}',
                    imageAlt: "Loader Image",
                    width: "400px",
                    allowOutsideClick: false,
                    showConfirmButton: false
        });
    }

    function success(message = 'Successfully Message Added'){
        Swal.fire({
                    title: message,
                    imageUrl: '{{ asset('loader/Success.gif') }}',
                    imageAlt: "Successfully Message Added",
                    width: "400px",
                    allowOutsideClick: false,
                    showConfirmButton: true
        }).then((result) => {
        if (result['isConfirmed']){
        // Put your function here
        location.reload();
        }
        });
    }

    function successmaintain(datatable, message = 'Successfully Message Added'){
        if(datatable){
            Swal.fire({
                    title: message,
                    imageUrl: '{{ asset('loader/Success.gif') }}',
                    imageAlt: "Successfully Message Added",
                    width: "400px",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    closeOnConfirm : true
             }).then((result) => {
            if (result['isConfirmed']){
                // Put your function here
                $('#'+datatable).DataTable().draw();


            }
            });
        }

    }

    function smssuccess(){
        Swal.fire({
                    title: "SMS Success",
                    imageUrl: '{{ asset('loader/SMSsending.gif') }}',
                    imageAlt: "SMS Success",
                    width: "400px",
                    allowOutsideClick: false,
                    showConfirmButton: true
        }).then((result) => {
        if (result['isConfirmed']){
        // Put your function here
        location.reload();
        }
        });
    }



    function error(error = "Something Went Wrong/Contact IT"){
        Swal.fire({
                    title: error,
                    imageUrl: '{{ asset('loader/Error.gif') }}',
                    imageAlt: "Something Went Wrong",
                    width: "400px",
                    showConfirmButton: true

        });
    }

    function validation(){
        Swal.fire({
                    title: "Please fill up the required Fields/This field is already taken",
                    imageUrl: '{{ asset('loader/Error.gif') }}',
                    imageAlt: "Something Went Wrong",
                    width: "400px",
                    showConfirmButton: true

        });
    }

    function numberValidation(message = 'Incorrect Format Of Number'){
        Swal.fire({
                    title: message,
                    imageUrl: '{{ asset('loader/Error.gif') }}',
                    imageAlt: "Something Went Wrong",
                    width: "400px",
                    showConfirmButton: true

        });
    }


</script>
