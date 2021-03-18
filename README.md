# Stellify
 
Websites are, to varying degrees, constructed from database queries. In practice, this tends to be take the form of a piecemeal approach whereby data is identified as needing to be "dynamic" and then steps are taken to store this data as a field in a database table. Records are then retrieved when needed for inclusion in HTML markup that exists in template files. The aim of Stellify is to take a different approach, one that involves storing *all* the data required to define a page in a database. 

How does it do this? Well the best way to illustrate the different approach taken by Stellify is to answer that very question, so let's do that.


### Defining elements as objects


All webpages are constructed from elements. An individual element looks like this:

```

<a href="/" class="relative ml-3">Visit Stellisoft</a>

```

We can define and store a representation of this element in a database as a JSON object:

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

Some elements however, are nested within other elements:

```

<a href="/" class="relative">
	<span class="text-red-500">Visit Stellisoft</span
</a>

```

If we want to depict nesting an element using a JSON, we attach the reference of the nested element in the `data` array of the object definition:

```
{

"id": "f8d1ca9c-34b1-11eb-adc1-0242ac120002",

"type": "s-element",

"text": "Visit Stellisoft",

"data": ["28f9b9c2-87e2-11eb-8dcd-0242ac130003"]

"tag": "a",

"href": "/",

"classes": ["relative", "ml-3"]

}

```

Then we define the nested element itself:

```

{

"id": "28f9b9c2-87e2-11eb-8dcd-0242ac130003",

"type": "s-element",

"text": "Visit Stellisoft",

"tag": "span",

"classes": ["text-red-500"]

}

```

Notice how the `id` field corresponds to the single reference found in the `data`  array of the parent element definition.

So let's assume we have our elements stored in a database, next we need to render them in the browser.



### Attach elements to form a page

  

The first step to displaying our elements is to attach them to a page definition. Yes, pages are also stored as JSON objects. They include an array of references to your element JSON object definitions. The `data` array on a page object contains the top level elements found on a page and the `blocks` array references all the element definitions found on a page (except those that are globally accessible, we'll come to that shortly).

```

{

"id": "1614476a-34bc-11eb-adc1-0242ac120002",

"title": "My First Stellify Webpage",

"data": ["f8d1ca9c-34b1-11eb-adc1-0242ac120002"],

"blocks: ["28f9b9c2-87e2-11eb-8dcd-0242ac130003"]

}

```

### The Request

As with all webpages, a request is made for a server to return a resource. Only in this instance, the primary objective is to retrieve the JSON objects that are required to render the requested page (rather than to navigate through and buffer the various template files using logic gates to determine how to formulate the requested page). With Stellify, the logic has already been defined in the database itself and therefore such calculations just aren't required. It's not an altogether different situation to the use of cache to serve up a dynamic page and like with cache, if a change has taken place then it will be recognised. It is entirely possible (and quite a cool feature) to render the definitions on the server and serve the page, this approach uses components that mirror the components used for client side rendering in terms of the way that the markup/HTML is constructed, however, these components still require javascript equivalents to make the markup dynamic once displayed in the browser, unless your page is to be entirely static of course!


### The Response

Sticking with client side rendering. The definitions we requested are attached to the browser window and are therefore accessible to scripts running on the page. The application's script processes the top level element definitions and then proceeds to recursively attached them to the Document Object Model (DOM).


### Types of Elements

We categorise elements into various types. We do this as some elements have lots of attributes that are only applicable to their own function and therefore it would be wasteful and disorganised to cram all the attributes and functions into one large element. Here are some of the main elements you can use to build a webpage in Stellify:

 - Presentational Element (Used for tags such as H1, a, img) 
 - Media Element (Video, Audio, Picture) 
 - Form Element (Form) 
 - Input Element  (Textinput, Checkbox, Radio Button, Select dropdown) 
 - Embed Element (iFrame, Object)

You can create just about anything with these elements. They are the same set of elements used on all webpages on the WWW!

### Global element definitions

We extend the concept of storing definitions as JSON to encompass the settings that apply to your entire website or web app. 

It is possible to store definitions that are *always* fetched. This is useful for headers, footers and any element(s) that reoccur across your entire website.


### Events

Elements have their own event ecosystem that allows them to watch and interact with other elements based upon factors such as "current state". This is an advanced topic and we'll producing documentation on our [website](https://stellisoft.com) very soon.


###  Templates (grouping of elements and associated data)

It's often impractical to develop entire webpages by adding individual elements. This can be due to time or complexity. Sometimes it makes sense to simply use a template. In Stellify, templates are elements that have been grouped together and in some cases they have been configured to communicate with one another to perform tasks such as displaying a group of elements that depict a modal window, or hiding text in an input box. We're always developing new and interesting templates and we invite you to do so too.

### The result?

This approach comes with lots of advantages:

 - No more file templates.
 - Edit your page content right down to element level.
 - Variables that display data within elements are themselves variable and therefore your data is completely detached from presentation, giving you the freedom and flexibility that web developers have always craved.
 - Greater consistency and organisation  = Less errors.
 - Ability to switch between a server rendered page or a client side rendered page.
 - Make backups at any time. You can store the data that makes up your webpage or site however and wherever you like. Store multiple copies and toggle between which "set" is retrieved for A/B testing purposes, share with colleagues, put it on a pen drive, you have complete freedom to do what you wish!

There are **lots** more advantages that are specific to various use cases. Why not come to our [website](https://stellisoft.com) and talk us about what you're doing?

### Live demo

You can see this in action [here](https://stellisoft.com?edit). 

  
### Get involved

We're making use of GitHub's Projects, so you can see what we're working on and get involved if you wish. For more information or to make enquires, please visit our [website](https://stellisoft.com)