$(document).ready(function () {

    (function ($) {
        "use strict";


        jQuery.validator.addMethod('answercheck', function (value, element) {
            return this.optional(element) || /^\bcat\b$/.test(value)
        }, "type the correct answer -_-");

        // validate contactForm form
        $(function () {
            $('#contactForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    subject: {
                        required: true,
                        minlength: 4
                    },
                    number: {
                        required: false,
                        minlength: 5
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    message: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    name: {
                        required: "come on, you have a name, don't you?",
                        minlength: "your name must consist of at least 2 characters"
                    },
                    subject: {
                        required: "come on, you have a subject, don't you?",
                        minlength: "your subject must consist of at least 4 characters"
                    },
                    number: {
                        required: "come on, you have a number, don't you?",
                        minlength: "your Number must consist of at least 5 characters"
                    },
                    email: {
                        required: "no email, no message"
                    },
                    message: {
                        required: "um...yea, you have to write something to send this form.",
                        minlength: "thats all? really?"
                    }
                },
                submitHandler: function (form) {
                    $(form).ajaxSubmit({
                        type: "POST",
                        data: $(form).serialize(),
                        url: "contact_process.php",
                        success: function (response) {
                            try {
                                const res = typeof response === 'string' ? JSON.parse(response) : response;
                                if (res.status === 'success') {
                                    Toastify({
                                        text: res.message,
                                        duration: 5000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                    }).showToast();
                                    $('#contactForm')[0].reset();
                                } else {
                                    Toastify({
                                        text: res.message,
                                        duration: 5000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                    }).showToast();
                                }
                            } catch (e) {
                                console.error("Parsing error:", e, response);
                                Toastify({
                                    text: "Response error. Please check browser console or contact support.",
                                    duration: 5000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                }).showToast();
                            }
                        },
                        error: function () {
                            Toastify({
                                text: "Oops! Something went wrong. Please try again later.",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            }).showToast();
                        }
                    })
                }
            })
        })

    })(jQuery)
})