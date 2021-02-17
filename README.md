# FABIII-WordPress-Plugin
WordPress Plugin publishig Member States' bailiffs data

Development of an open-source WordPress Plugin endpoints publishing real-time data to the FAB Directory. 

This solution aims to satisfy the integration of National Registries with existing CMS frameworks but without having REST API capabilities, in order to connect to the FAB Directory. The Plugin builds the REST API endpoints upon the existing WordPress installation.

In order for the Plugin to be used, the **$args** of **get_posts** as well as of the **get_field** should be edited based on the installation's **custom post types** and the **custom fields**.

Also, the function get_field is used by the ACF Plugin. If the installation uses a fifferent way to create the data, the function **get_post_meta()** should be used.

Contact: fab3@it.auth.gr
