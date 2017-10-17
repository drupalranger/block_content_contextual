## Oh no! My contextual links are gone!
While rendering entities in a custom module, you may find that your markup is incorrect.. or rather incomplete,
and contextual links are not showing up. 

This very small example, will show you why is it happening when you try to render block content entity using [BlockContentViewBuilder](https://api.drupal.org/api/drupal/core%21modules%21block_content%21src%21BlockContentViewBuilder.php/class/BlockContentViewBuilder/8.4.x), 
but it might also happen while rendering content in any other way. 

### What you'll find here 
1. A theme hook - a very simple one, just to provide custom template quickly 
2. Twig template which will wrap our entity in a container with all required markup 
2. A route - yet another page callback, to show visual example 
3. Custom controller - very simple one, it will load block content entities and render em in two different ways to show different behaviours 

### How to run through 
1. Install this module using drush or UI, whatever
2. Navigate to /admin/structure/block/block-content and add some custom blocks 
3. Navigate to /examples/block_content_contextual and check the output, 
the page will show 2 lists of custom blocks ( with and without contextual links )
