NadiaFrameworkExtraBundle
===================

This bundle extends SensioFrameworkExtraBundle, provides more ways to configure your controllers with annotations.


## @ViewSwitch Annotation

Setup template name with different request formats (`_format` attribute in a Request instance). Add the annotation into your Controller Action, for example:

```php
/**
 * @ViewSwitch(format="html", view="default/edit.html.twig")
 * @ViewSwitch(format="modal.html", view="default/edit-modal.html.twig")
 */
```

The `@ViewSwitch` annotation has two properties:

- format: `_format` attribute in a Request instance
- view: twig template name


A complete example as below:

```php
// Controller/DefaultController.php
class DefaultController extends Controller
    /**
     * @param Request $request
     * @param int $id
     *
     * @return array
     *
     * @Route("/create.{_format}", name="create", requirements={"_format":"html|modal.html"}, defaults={"_format":"html"})
     * @Route("/edit/{id}.{_format}", name="edit", requirements={"id":"\d+", "_format":"html|modal.html"}, defaults={"_format":"html"})
     * @Method({"GET", "POST"})
     *
     * @ViewSwitch(format="html", view="default/edit.html.twig")
     * @ViewSwitch(format="modal.html", view="default/edit-modal.html.twig")
     */
    public function editAction(Request $request, $id = 0)
    {
        $form = $this->createFormBuilder()
            ->add('foo', TextType::class)
            ->add('bar', TextType::class)
            ->getForm()
            ->createView()
        ;

        return ['form' => $form];
    }
}
```

```twig
{# Resources/views/edit.modal.html.twig #}

{% block form %}
    {{ form(form) }}
{% endblock %}
```

```twig
{# Resources/views/edit.html.twig #}

<h1>Demo Form</h1>
{{ include('edit-modal.html.twig') }}
```
