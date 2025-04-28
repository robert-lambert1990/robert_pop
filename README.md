I've created a module that allows for an admin user to create a custom block where they can populate upto 20 ID's and the block will show these ID's by fetching the details from the Spotify API and presenting them in a list from the artist name.

The block is using a custom field widget I created that will allow the user to choose the music provider - In this case spotify - That when the block is render will use to render onto the page.

I choose to slightly deviate from the limitation of a spotify renderer and use a more global approach of a Music Provider, which allows for further expansion should we wish to use other cases such as Amazon or Apple.

I created an interface and an abstract class for the music provider to ensure that all new classes in the future still work within the application. There are issues here as there is an assumption that at the basic level the other API's will work in a similar way, if I was given more time I would delve in deeper, even implementing other music providers to ensure my code is ironclad.

I also created a custom settings form to help control the secret/client id's for spotify.
/admin/config/music-providers/spotify-settings

Each name if the current user is logged in the block will show the user each artist with a link to their own page - Within my custom form element I create a display mode that checks if that user is logged in or not and to return the correct content.

I opted to go down the route of creating a custom route that will accept a artist name then using the Spotify API create a page for the user presenting some basic information on the artist. The problem here is the potential for a user to access any artist if they know their name, if I had more time I would of expanded the access on the custom route making sure it matched the block content. I'm really against hard coding any type of ID into the template, so I could of created a settings form where the admin user can specify the block content which could be used as reference or a custom block / config form that held the content I want to admin user to populate to pull reference from.

Another option I debated was to create artist entities on the fly so we're not hammering the spotify api constantly - beyond adding in caching layers which was also a further implementation I would of added - This would give better control over the content since it exists within drupal, and since we'd be creating a taxonomy of genres it gives more options over the frontend. I would of created a lightweight app to show the artists within the block with the genres as a filter.

I finally added in a couple of phpunit tests.

I've added in a custom theme and setup a webpack to compile the scss/js but I haven't added any proper styling if this was continued and more time was given.
