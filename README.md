![enter image description here](https://stellifysoftware.s3.eu-west-2.amazonaws.com/stellisoftyellow.svg)

The primary aim of Stellify is to take the constituent parts that make up a webpage out of template files and into a database. You may point out that, with the exception of static site generators, all websites to some degree are constructed from database queries however, in practice this tends to be a piecemeal approach whereby data is identified as needing to be "dynamic" and then steps are taken to store this data as a field in a database table. This field is retrieved when needed for inclusion in HTML markup. The best way to illustrate the different approach taken by Stellify is to map out exactly what it does differently so that you can make your own comparisons and draw your own conclusions.

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

It's often impractical to develop entire webpages from atomic or even nested elements. Due to the either complexity of the functionality required and/ or the underlying implementation of an elements specification, sometimes it makes sense to create a component that acts as a wrapper around functionality or related data. This existing practise is often referred to as "creating components". There is a difference here however and it's worth highlighting as if we distinguish the difference we'll end up with the same issues existing frameworks encounter.  Components that are required in this system (Stellify) should still be atomic, not in terms of the markup but in other aspects they should be. This is perhaps best illustrated by explaining the inverse of what I've just said or put simply "what you shouldn't do".

Let's assume you envisaged a creating a component for a banner with two CTA buttons. If you were to create an object to represent this banner, what would that look like? How would you distingish between the two buttons? You'd end up with names such as `buttonOneLabel`, `buttonTwoLabel`. Where would the two buttons be located and how would you alter their position? The only solution would be to create multiple banner components and there we are, back using file templates. Banners are a great use case for atomic elements, no need to group these elements, it only serves to cause problems.

Components you would create for use with this system could be:

 - An SVG component as this involves creating lots of attributes and nested tags that affect the display of a graphic and therefore it makes sense to create a component that maps attributes to dynamic values and facilitates inner loops for nested tags that allow for animations and image manipulations.
 - A button warrants its own component as the majority of elements do not trigger events.
 - A product card or any component involving data that is connected in such a way that makes it impractical to divide it into individual objects.

Despite the language used here i.e. *advanced*, a definition of such an object is entirely consitent with the definitions we've looked at so far. By way of example, here's a definition that can render a rectangle shape using SVG:

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
As with all frameworks, a request is made for a resource. Only in this instance, the next step is to retrieve the objects that are required for requested route, rather than to navigate through and buffer the various template files using logic gates to determine how to formulate the requested page. With Stellify, the logic has already been defined in the database itself and therefore such calculations just aren't required. It's not an altogether different situation to the use of cache to serve up a dynamic page and like with cache, if a change has taken place it will be recognised.

### The Response
Various options/ paths exist for the way in which we can structure the response and also how to retrieve and organise the objects client side i.e. in the browser. The approach taken in this example repo is to assemble all the blocks required by the page as one large object "blob", keyed by each object's `id`, then attach it to the window object where it can be easily accessed from any script.

### The "Glue"
There is a key ingredient required in order to make this concept work. We need a code that is capable of rendering the element(s) defined by the objects on the page itself. The client-side Javascript framework VueJS provides this code in the form of their [dynamic component](https://vuejs.org/v2/guide/components.html#Dynamic-Components). Fortunately, most modern frameworks have a comparable method that achieves the same thing.

### Repository contents
This example repo contains a Laravel package that includes examples of the core files and routines needed to construct and render webpages from a database rather than file templates, as we've described.

The key files included are:
1. Page controller: We've included an example front controller that assesses the route and fetches the objects required by the route.
2. Utility controller: The true power of Stellify is in the manipulation of the objects stored in the database, we've provided some common routines that allow insertion, removal and duplication of objects.
3. Vue Components. In this implementation we are using VueJS to process and render webpage as defined by the object definitions sent to the browser when navigating to a page. We'd advise you look at all the asset files, we haven't included many and they do hold the key to explaining how this implementation of Stellify works.

### Live demo
You can see how this concept can be used to generate a webpage [here](https://stellisoft.com?edit). This demo includes an editor that allows for elements to be created, altered and populated as objects ready for storage in a database.

### Get involved
We're making use of GitHub's Projects, so you can see what we're working on and get involved if you wish. For all other enquires or information please visit our [website](https://stellisoft.com?edit)
