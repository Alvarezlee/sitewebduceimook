@props(['formSelector' => 'form'])

@if ($siteKey = config('services.recaptcha.site_key'))
    <input type="hidden" name="g-recaptcha-response" class="ceimo-recaptcha-token">
    <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('{{ $formSelector }}').forEach(function (form) {
                if (!form.querySelector('.ceimo-recaptcha-token') || form.dataset.recaptchaBound) {
                    return;
                }
                form.dataset.recaptchaBound = 'true';

                form.addEventListener('submit', function (event) {
                    if (form.dataset.recaptchaVerified === 'true') {
                        return;
                    }

                    event.preventDefault();

                    grecaptcha.ready(function () {
                        grecaptcha.execute('{{ $siteKey }}', { action: 'submit' }).then(function (token) {
                            form.querySelector('.ceimo-recaptcha-token').value = token;
                            form.dataset.recaptchaVerified = 'true';
                            form.submit();
                        });
                    });
                });
            });
        });
    </script>
@endif
