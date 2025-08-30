
<!-- following js will activate the menu in left side bar based on url -->
<script type="text/javascript">
    // === following js will activate the menu in left side bar based on url ====
    $(document).ready(function() {
        $("#sidebar-menu a").each(function() {
            if (this.href == window.location.href) {
                $(this).addClass("active");
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
    });
</script>

<!-- INICIO - Widget Jira Service Desk -->
<script data-jsd-embedded data-key="21083e4a-e884-4379-b9d9-57f1f92b6b74" data-base-url="https://jsd-widget.atlassian.com" src="https://jsd-widget.atlassian.com/assets/embed.js"></script>

<!-- Alinea el Widget a la izquierda -->
<style>
    iframe[name='JSD widget'] {
        display: block;
        left: 0;
        bottom: 10px;
    }
</style>

<!-- FIN - Widget Jira Service Desk -->


</body>
</html>