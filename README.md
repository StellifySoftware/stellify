![enter image description here](https://stellifysoftware.s3.eu-west-2.amazonaws.com/stellisoftyellow.svg)

The primary aim of Stellify is to take the definition of a webpage out of files and into a database. You may point out that with the exception of static site generators, all websites are constructed "from" database queries however,  this tends to be a piecemeal approach whereby data is identified as being "dynamic" and then steps are taken to insert this data into HTML markup or the data is passed to a script and then applied a file based template. **I'd ask you to put that approach to one side as this is not what is happening inside Stellify.** 

### Stellify maps objects to elements

Stellify takes an atomic approach to storing definitions. In HTML an *element* is indivisible and therefore it is possible to map an element to an object as shown below:

```
<a href="/" class="relative ml-3">
```
In Stellify, this element is stored as JSON definition:

```
{
	"id": "f8d1ca9c-34b1-11eb-adc1-0242ac120002",
	"type": "s-element",
	"tag": "a",
	"href": "",
	"classes": ["relative", "ml-3"]
}
```

### Nested elements

Starting with a `div`, if we want to nest an element we attach the reference of the nested element in the `data` array of the div's object definition:
```
{
	"id": "dde2418a-34b6-11eb-adc1-0242ac120002",
	"type": "s-element",
	"tag": "div",
	"data": ["f8d1ca9c-34b1-11eb-adc1-0242ac120002"]
}
```
Then to define the nested element:
```
{
	"id": "f8d1ca9c-34b1-11eb-adc1-0242ac120002",
	"type": "s-element",
	"tag": "a",
	"href": "",
	"classes": ["relative", "ml-3"]
}
```

### Advanced components

It's impractical to develop entire webpages from elements, due to the either complexity of the functionality required and/ or the underlying implementation of an element. In these instances, it makes sense to create a component to act as a wrapper around functionality or related data. Some examples that spring to mind would be:

 - SVG elements that have been implemented in such a way that they involve lots of attributes and nested tags that affect the display of a graphic.
 - A button that triggers events.
 - A product card or any component involving data that is connected in such a way that makes it impractical to divide it into individual objects.

### Repository contents
This repo contains a Laravel package that includes examples of the core files and routines required to construct webpages from a database rather than file templates as described.

Key files include:
1. Front controller. We've included an example front controller that assesses the route and fetches the objects required by the route.
2. Utility routines. The true power of Stellify is in the manipulation of the objects stored in the database, we've provided some common routines that allow insertion, removal and duplication of objects,.
3. Vue Components. In this implementation we are using VueJS to process and render webpage as defined by the object definitions sent to the browser when navigating to a page.

### Live demo

You can see how these objects can be used to form a webpage [here](https://stellisoft.com?edit). The demo includes an editor that allows for elements to be created and populated as objects ready for storage. As mentioned, the real power of Stellify is in manipulating the objects once they are stored to create new objects using existing definitions, adding to or amending stored objects using routines and duplicating objects to replicate functionality across pages of a website.