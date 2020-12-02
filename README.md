![enter image description here](https://stellifysoftware.s3.eu-west-2.amazonaws.com/stellisoftyellow.svg)

The primary aim of Stellify is to take the definition of a webpage away from files and put it in a database. You may point out that with the exception of static site generators, all websites are constructed "from" database queries however, this tends to be a piecemeal approach whereby data is identified as being "dynamic" and then steps are taken to insert this data into HTML markup on the server or the data is passed to a script and then inserted into the DOM. **I'd ask you to put that approach to one side as this is not what is happening inside Stellify. Want to know more? Read on and I'll explain how Stellify approaches  dynamic content differently.** 

### Defining elements as objects

All webpages are constructed from elements. An individual element can be thought of as being atomic, meaning it is indivisible.

```
<a href="/" class="relative ml-3">Visit Stellisoft</a>
```

This element can be defined and stored in a database as a JSON object:

```
{
	"id": "f8d1ca9c-34b1-11eb-adc1-0242ac120002",
	"type": "s-element",
	"text": "Visit Stellisoft",
	"tag": "a",
	"href": "/",
	"classes": ["relative", "ml-3"]
}
```

### Grouping nested elements

If we want to nest an element within another element, which is common practice, we attach the reference of the nested element in the `data` array of the object definition:
```
{
	"id": "dde2418a-34b6-11eb-adc1-0242ac120002",
	"type": "s-element",
	"tag": "div",
	"data": ["f8d1ca9c-34b1-11eb-adc1-0242ac120002"]
}
```
Then to define the nested element itself:
```
{
	"id": "f8d1ca9c-34b1-11eb-adc1-0242ac120002",
	"type": "s-element",
	"text": "Visit Stellisoft",
	"tag": "a",
	"href": "/",
	"classes": ["relative", "ml-3"]
}
```

### Grouping elements to form a page

Pages are stored as objects that include an array of references to top level object definitions:

```
{
	"id": "1614476a-34bc-11eb-adc1-0242ac120002",
	"title": "My First Stellify Webpage",
	"data": ["dde2418a-34b6-11eb-adc1-0242ac120002"]
}
```

### Advanced components (grouping of elements and associated data)

It's often impractical to develop entire webpages from atomic or even nested elements. Due to the either complexity of the functionality required and/ or the underlying implementation of an elements specification, sometimes it makes sense to create a component that acts as a wrapper around functionality or related data. Some examples that spring to mind would be:

 - SVG elements involve lots of attributes and nested tags that affect the display of a graphic and therefore it makes sense to create a component.
 - A button warrants its own component as the majority of elements do not trigger events.
 - A product card or any component involving data that is connected in such a way that makes it impractical to divide it into individual objects.

Despite the language used here i.e. *advanced*, a definition of such an object is entirely consitent with the definitions we've looked at so far. By way of example, here's a definition that will render a rectangle shape using SVG:

```
{
	"id": "16b24590-349a-11eb-adc1-0242ac120002",
	"tag": "rect",
	"type": "svg",
	"classes": [
		"w-64",
		"h-64",
		"from-purple-500",
		"to-blue-800"
	],
	"viewBox": "0 0 1200 400",
	"x": 300,
	"y": 100,
	"fill": "url('#linearGradient')",
	"width": "24",
	"height": "24"
}
```

### The Request
As with all frameworks, a request is made for a resource. Only this instance, the next step is to retrieve the objects that are required for requested route, rather than to navigate through and buffer the various template files and logic gates to formulate a page. In this instance the logic is is already defined in the database and therefore such calculations just aren't required.

### The Response
Various options/ paths exist for the way in which we structure the response and also how to retrieve and organise the objects client side i.e. in the browser. The approach taken in this repo is to attach all the blocks required by the page as one large object "blob", keyed by each object's `id`, to the window object from which it can be accessed from a script. This allows the objects to be retrieved easily from anywhere within your scripts.

### The "Glue"
There is a key ingredient required in order to make this concept work. We need a code that is capable of rendering the element(s) defined by the objects on the page itself. The client-side framework VueJS provides this code in the form of their [dynamic component](https://vuejs.org/v2/guide/components.html#Dynamic-Components). Most modern frameworks have a comparable method that achieves the same thing.

### Repository contents
This repo contains a Laravel package that includes examples of the core files and routines required to construct and render webpages from a database rather than file templates as described.

Key files include:
1. Front controller. We've included an example front controller that assesses the route and fetches the objects required by the route.
2. Utility routines. The true power of Stellify is in the manipulation of the objects stored in the database, we've provided some common routines that allow insertion, removal and duplication of objects,.
3. Vue Components. In this implementation we are using VueJS to process and render webpage as defined by the object definitions sent to the browser when navigating to a page.

### Live demo
You can see how this concept can be used to generate a webpage [here](https://stellisoft.com?edit). This demo includes an editor that allows for elements to be created, altered and populated as objects ready for storage in a database.

The real power of this approach is in the manipulation these objects (once they are stored) to create new objects using existing definitions, adding to or amending stored objects using routines and duplicating objects to replicate functionality across pages of a website.