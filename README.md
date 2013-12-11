VMG Nested
=====

A plugin for ExpressionEngine that allows for more flexibility when nesting module tags with the ability to add variable prefixes to any tags. Also allows exp:channel:entries tags to be nested without the need for embeds.

Installation
------

* Add the vmg_nested folder to system/expressionengine/third_party
* Check that it shows in the control panel Plugins list

Usage
-------

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
        {exp:vmg_nested:tag:entries prefix="tag_entry"}
                {tag_entry:title}
                {tag_entry:url_title}
                {tag_entry:custom_field}
        {exp:vmg_nested:tag}

        {exp:vmg_nested:profile:view member_id="{author_id}" prefix="author" parse="inward"}
                {author:title}
                {author:cf_profile_about_me}
                {exp:ce_img:single src="{author:cf_profile_image}" max_width="70" max_height="70"}
        {/exp:vmg_nested:profile:view}
{/exp:channel:entries}
```

Compatibility
---------

Requires ExpressionEngine 2.5.0+ and PHP 5.3+

Warranty/License
--------
There's no warranty of any kind. If you find a bug, please report it or submit a pull request with a fix. It's provided completely as-is; if something breaks, you lose data, or something else bad happens, the author(s) and owner(s) of this add-on are in no way responsible.

This add-on is owned by [Vector Media Group, Inc](http://www.vectormediagroup.com). You can modify it and use it for your own personal or commercial projects, but you can't redistribute it.
