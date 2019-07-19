// Load the CSS stuff
require('@fortawesome/fontawesome-free/css/fontawesome.min.css');
require('@fortawesome/fontawesome-free/css/brands.min.css');
require('@fortawesome/fontawesome-free/css/solid.min.css');
require('../css/app.scss');

// Load the JS stuff
let $ = require('jquery');
require('bootstrap');
require('./libs/navbar.js');

// Syntax Highlighter
require('prismjs');
require('prismjs/components/prism-nginx');

var { DateTime } = require('luxon');

$(document).ready(function () {
    convertDates();
    $("#documentation_deployment_repositoryUrl").change(function(){
        const value = $("#documentation_deployment_repositoryUrl").val();
        if (value.indexOf('https://github.com') === 0) {
            $("#documentation_deployment_repositoryType option[value=github]").removeAttr('disabled');
            $("#documentation_deployment_repositoryType").val('github').attr('readonly', true);
            $("#documentation_deployment_repositoryType option:selected").siblings().attr('disabled', 'disabled');
        } else if (value.indexOf('https://gitlab.com') === 0) {
            $("#documentation_deployment_repositoryType option[value=gitlab]").removeAttr('disabled');
            $("#documentation_deployment_repositoryType").val('gitlab').attr('readonly', true);
            $("#documentation_deployment_repositoryType option:selected").siblings().attr('disabled', 'disabled');
        } else if (value.indexOf('https://bitbucket.org') === 0) {
            $("#documentation_deployment_repositoryType option[value=bitbucket-cloud]").removeAttr('disabled');
            $("#documentation_deployment_repositoryType").val('bitbucket-cloud').attr('readonly', true);
            $("#documentation_deployment_repositoryType option:selected").siblings().attr('disabled', 'disabled');
        } else {
            $("#documentation_deployment_repositoryType").removeAttr('readonly');
            $("#documentation_deployment_repositoryType option").filter(function() {
                return this.value.length !== 0;
            }).removeAttr('disabled');
        }
    });
});

function convertDates() {
    Array.from(document.querySelectorAll('[data-processor="localdate"]')).forEach(function (element) {
        const value = element.dataset.value;
        element.textContent = DateTime.fromISO(value).toLocaleString({ 
            month: '2-digit',
            day: '2-digit',
            year: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    });
}

