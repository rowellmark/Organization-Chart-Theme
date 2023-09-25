

### Custom jOrgchart [jquery.orgchart-a99.min] 
* The main function uses <code>ul</code> as data source.
* Attribute <code>id</code> in <code>li</code> tag will only accept (int)post-id, for departments the id must start with letter <code>"d"</code>.
* <code>node-center</code> wrapper added inside node box to group the contents.


### The custom script adds the following attributes.
* <b>data-link</b> attribute:<br>
    * This is placed in <code>li</code> tag as attribute. Possible values: <code>post id</code> or hash text <code>#your-sample-text</code>

* <b>data-class</b> attribute:<br>
    * This is placed in <code>li</code> tag as attribute.
    * This tag is used to add custom class in node.

* <b>span</b> tag:<br>
    * This is placed inside <code>li</code> tag. (needs to be placed exactly at the end of li tag) ex: <code> &#60;li&#62;&#60;span&#62;Node Title&#60;&#47;span&#62; </code>.
    * The script will fail to read the text when there's a line break or space after the <code>li</code> tag.

* <b>em</b> tag: <br>
    * This is placed inside <code>li > span</code> tag. ex: <code> &#60;li&#62;&#60;span&#62;Node Title &#60;em&#62;Node Description&#60;&#47;em&#62;&#60;&#47;span&#62; </code>.