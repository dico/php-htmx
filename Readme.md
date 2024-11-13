# PHP-HTMX
This application serves as a testing ground for HTMX with a PHP backend. It can be considered a minimal, bootstrap-like setup for this purpose.

### Goals
- [x] Use as few frameworks and packages as possible, while avoiding reinventing the wheel. Any chosen packages should be non-critical (i.e., can be rewritten quickly if they become unsupported).
- [x] Respond with both JSON and HTML based on the client’s `Accept` header, allowing it to function as both a JSON REST API and an HTML response server for HTMX.

### Todo
- [ ]  Create larger tests with a database.
- [ ]  Make a pretty frontend for a more realistic use case, like create, update and delete records. 


## Revision

### No base URL
There is no way to set a base URL i HTMX. The goal was to get an environment variable from docker (for the backend API) and set it as base URL for the HTMX requests.
After several hours of with Google, ChatGPT and testing things like addEventListener to 'htmx:configRequest' or <base>, it just did not work. The hx-trigger="load", would load from the same origin before we could change the URL.

Making a template and swapping URLs in the build process works, but it add complexity for e.g. development environment - as we don't want to build for every time we save the page.

The solution was to merge backend and frontend as two seperate containers into one. The API is available at /api on the same origin. If we have an application that conquer the world somehow, it's possible to split the containers and do some routing in a proxy.




## Prerequisites

- You can run it as you want, but it's testet locally with docker as the "developer server" using [nginx-proxy/nginx-proxy](https://github.com/nginx-proxy/nginx-proxy) and [sebastienheyd/docker-self-signed-proxy-companion](https://github.com/sebastienheyd/docker-self-signed-proxy-companion). The environment-variables lile VIRTUAL_HOST and SELF_SIGNED_HOST in the docker-compose.yaml are used for the proxy.
- Rename .env-sample to .env and update it.




## Background and Motivation

Most tutorials for HTMX, both written and on YouTube, cover only the basics. So far, I haven’t found a guide that explains how to implement HTMX in a real-world environment.

In the early days of web applications, PHP was often the go-to solution. We would use PHP to handle database interactions, handle GET and POST requests, and navigate via URLs. At that time, "state" was rarely discussed as a concept.

With a single application instance, one cohesive "state" was often enough, leading to the popularity of the full-stack PHP approach. To make applications more modular and flexible, MVC (Model-View-Controller) frameworks gained traction.

The rise of mobile applications and the need to scale web services changed the landscape, pushing us toward a "Backend <-> Frontend" model. While MVC could still be used on the backend, the View layer was no longer needed, giving rise to hybrid approaches.

PHP 5 also led many developers to consider other languages for web development, further diversifying the tech landscape. With this backend-frontend split, frontend frameworks like React and Vue emerged, which brought advantages by letting developers split the frontend into components and manage the DOM state effectively.

However, the shift to JSON APIs and the virtual DOM introduced complexity. Where we once might have built a simple PHP application in Notepad++, we now needed a backend to fetch data from the database, transform it into JSON, send it to the frontend, and process it into a virtual DOM that would then update the actual DOM. The modern stack also brought dependencies on terminal tools, package managers, and additional infrastructure.

### The Complexity Problem

Over-reliance on package managers is increasingly common. While package managers are convenient, they can bring unexpected consequences, as illustrated in [How one programmer broke the internet by deleting a tiny piece of code](https://qz.com/646467/how-one-programmer-broke-the-internet-by-deleting-a-tiny-piece-of-code). Today’s packages often don’t just solve a single problem (as was the case in the example above) and have grown to such complexity that they can almost function as languages of their own.

For instance, React's codebase alone can weigh 150-200 MB, with around 40,000 lines of code before any project-specific code is added. This adds layers of complexity—not only in the code itself, but also in compiling, understanding, and transporting it.

What happens when a framework or package is no longer supported or undergoes a major version shift, as in the case of AngularJS 1.0 to Angular 2.0? In many cases, this means rewriting significant portions of the application. Chad Fowler, in his presentation on microservices architecture for Wunderlist, highlighted the importance of modularity with the goal, “Then they can rewrite it if there is a problem.” But if an unsupported or significantly changed framework underpins the application, rewriting becomes a more daunting prospect.

[From Homogeneous Monolith to Heterogeneous Microservices Architecture • Chad Fowler • GOTO 2015](https://www.youtube.com/watch?v=sAsRtZEGMMQ)

## HTMX
HTMX aims to address the modern web’s overhead by taking us back to the basics of web development, allowing applications to send HTML directly to the browser. This approach minimizes frontend JavaScript, improves performance, and removes much of the complexity.

The McMaster’s site ([How is this Website so fast!?](https://www.youtube.com/watch?v=-Ln-8QM8KhQ)) is a well-known example of how fast a site can be with such an approach. They use techniques, such as sending HTML directly to the frontend. While they may not specifically use HTMX, they’ve implemented optimizations that demonstrate HTML's performance advantages over JSON.

At DjangoCon 2022, David Guillot shared [a case study](https://www.youtube.com/watch?v=3GObi93tjZI) showing significant performance improvements from switching from React to HTMX. Their application got about 50% faster, and memory usage was reduced by about 50%.
