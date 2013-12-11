VMG Nested
==========

A plugin for ExpressionEngine that allows for more flexibility when nesting module tags with the ability to add variable prefixes to any tags. Also allows exp:channel:entries tags to be nested without the need for embeds.


Usage
-------

Requires ExpressionEngine 2.5.0+ and PHP 5.3+

```
{exp:channel:entries channel="news"}
        {exp:vmg_nested:channel:entries channel="blog" prefix="blog"}
                {blog:title}
                {blog:entry_id}
                {blog:count}
                
                {if blog:no_results}{redirect="404"}{/if}
        {/exp:vmg_nested:channel:entries}

        {!-- Third Party Modules. All tag parametrs from the original
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
