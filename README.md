VMG Nested
=====

An extremely flexible plugin for ExpressionEngine that allows for more flexibility when nesting any module and plugin tags within others, including the ability to add variable prefixes to any tags. **VMG Nested supports third party modules and plugins**.

You can nest the same tag multiple times, with different parameter prefixes on each level.

One of many uses for this is allowing exp:channel:entries tags to be nested inside of each other without the need for embeds.

Installation
------

* Add the vmg_nested folder to system/expressionengine/third_party
* Check that it shows in the control panel Plugins list

Usage
-------

For best results, use the `prefix=''` parameter to avoid collisions. You can use the other module or plugin tag as you normally would, but instead of starting the tag name `{exp:`, start it with `{exp:vmg_nested:`. 

For instance, let's say you want to nest an `{exp:channel:entries}` tag inside another one. Use the outside one as normal, and then inside use something like `{exp:vmg_nested:channel_entries prefix='inner'}` (with any prefix you'd like).

```
{exp:channel:entries channel="news"}
        {exp:vmg_nested:channel:entries channel="blog" prefix="blog"}
                {blog:title}
                {blog:entry_id}
                {blog:count}
                
                {if blog:no_results}{redirect="404"}{/if}
        {/exp:vmg_nested:channel:entries}

        {!-- Third Party Modules. All tag parameters from the original
                module tag can be used. --}

        {!-- Example: Solspace Tag --}
        {exp:vmg_nested:tag:entries prefix="tag_entry"}
                {tag_entry:title}
                {tag_entry:url_title}
                {tag_entry:custom_field}
        {exp:vmg_nested:tag}

        {!-- Example: Profile:Edit module --}
        {exp:vmg_nested:profile:view member_id="{author_id}" prefix="author" parse="inward"}
                {author:title}
                {author:cf_profile_about_me}
                {exp:ce_img:single src="{author:cf_profile_image}" max_width="70" max_height="70"}
        {/exp:vmg_nested:profile:view}
{/exp:channel:entries}
```

Compatibility
---------

We've tested this with a number of third party addons and haven't found any issues, but let us know if you find anything it doesn't work with. It'd be helpful to supply relevant version numbers.

VMG Nested requires ExpressionEngine 2.5.0+ and PHP 5.3+.

Warranty/License
--------
There's no warranty of any kind. If you find a bug, please report it or submit a pull request with a fix. It's provided completely as-is; if something breaks, you lose data, or something else bad happens, the author(s) and owner(s) of this add-on are in no way responsible.

This add-on is owned by [Vector Media Group, Inc](http://www.vectormediagroup.com). You can modify it and use it for your own personal or commercial projects, but you can't redistribute it.
