/**
 * @version    $Id$
 * @package    JSN_Uniform
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
( function ($) {
    "use strict";
    $(window).load(function () {
        var form = $('.paymentgateway-settings #profile-form'),
            test_mode = $("input[name='jform[test_mode]']:checked"),
            payment = $("select[name='jform[payment_product]']");
        if (test_mode.val() == '1')
        {
            if(payment.val() == 'standard')
            {
                form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apilivestd]').parents().removeClass('active')
                form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apisandboxstd]').parents().addClass('active')
                form.find('div#jsn-uniform-paypalstdContent div#apilivestd').removeClass('active')
                form.find('div#jsn-uniform-paypalstdContent div#apisandboxstd').addClass('active')
            }
            else
            {
                form.find('ul#jsn-uniform-paypalTabs li a[href=#apilive]').parents().removeClass('active')
                form.find('ul#jsn-uniform-paypalTabs li a[href=#apisandbox]').parents().addClass('active')
                form.find('div#jsn-uniform-paypalContent div#apilive').removeClass('active')
                form.find('div#jsn-uniform-paypalContent div#apisandbox').addClass('active')
            }
        }
        $("select[name='jform[payment_product]']").on('change', function(){
            if($(this).val() == 'standard')
            {
                $('#jsn-uniform-paypalTabs').parents('fieldset').addClass('hidden')
                $('#jsn-uniform-paypalstdTabs').parents('fieldset').removeClass('hidden')
                if (test_mode.val() == '1')
                {
                    form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apilivestd]').parents().removeClass('active')
                    form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apisandboxstd]').parents().addClass('active')
                    form.find('div#jsn-uniform-paypalstdContent div#apilivestd').removeClass('active')
                    form.find('div#jsn-uniform-paypalstdContent div#apisandboxstd').addClass('active')
                }
            }
            else
            {
                $('#jsn-uniform-paypalTabs').parents('fieldset').removeClass('hidden')
                $('#jsn-uniform-paypalstdTabs').parents('fieldset').addClass('hidden')
            }
        }).trigger('change');

        $("input[name='jform[test_mode]']").on('change', function(){

                if($(this).val() == '1'){
                    if(payment.val() == 'standard')
                    {
                        form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apilivestd]').parents().removeClass('active')
                        form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apisandboxstd]').parents().addClass('active')
                        form.find('div#jsn-uniform-paypalstdContent div#apilivestd').removeClass('active')
                        form.find('div#jsn-uniform-paypalstdContent div#apisandboxstd').addClass('active')
                    }
                    else
                    {
                        form.find('ul#jsn-uniform-paypalTabs li a[href=#apilive]').parents().removeClass('active')
                        form.find('ul#jsn-uniform-paypalTabs li a[href=#apisandbox]').parents().addClass('active')
                        form.find('div#jsn-uniform-paypalContent div#apilive').removeClass('active')
                        form.find('div#jsn-uniform-paypalContent div#apisandbox').addClass('active')
                    }

                }
                else
                {
                    if(payment.val() == 'standard')
                    {
                        form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apilivestd]').parents().addClass('active')
                        form.find('ul#jsn-uniform-paypalstdTabs li a[href=#apisandboxstd]').parents().removeClass('active')
                        form.find('div#jsn-uniform-paypalstdContent div#apilivestd').addClass('active')
                        form.find('div#jsn-uniform-paypalstdContent div#apisandboxstd').removeClass('active')
                    }
                    else
                    {
                        form.find('ul#jsn-uniform-paypalTabs li a[href=#apilive]').parents().addClass('active')
                        form.find('ul#jsn-uniform-paypalTabs li a[href=#apisandbox]').parents().removeClass('active')
                        form.find('div#jsn-uniform-paypalContent div#apilive').addClass('active')
                        form.find('div#jsn-uniform-paypalContent div#apisandbox').removeClass('active')
                    }

                }

            }

        );

    });

})((typeof JoomlaShine != 'undefined' && typeof JoomlaShine.jQuery != 'undefined') ? JoomlaShine.jQuery : jQuery);
