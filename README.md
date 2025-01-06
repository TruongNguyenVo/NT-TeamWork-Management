`Update [4/1/2024]`
# Project:
## Start Project:
1. Change policy in `PowerShell` to allow download scripts in internet if this have valid signature in currently user
```
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```
1. Install Scoop: `Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression`
2. Install Symfony: `scoop install symfony-cli`
3. Setting Database in .env file with database type is `mysql` and database's name is `trainingproject`
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=mariadb-10.4.11&charset=utf8mb4"
```
4. Create database: `php bin/console doctrine:database:create`
5. Create Entity: `php bin/console make:entity category`
6. Make migration: `php bin/console make:migration`
7. Migrate: `php bin/console doctrine:migrations:migrate`
8. Create Controller: `php bin/console make:crud`
9. Run server: `symfony server:start`

## Make Relationship in Product with Category
1. Create Entity Product: `php bin/console make:entity product`
2. Add some field like `Name`, `Description`
3. `Add another property? => category`
4. `Field type? => relation`
5. `What class should .... => Category`
6. `Relation type?.... => ManyToOne`


# Error:
## The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue.
Add serverVersion=mariadb-10.4.11 when you see this issue.
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=mariadb-10.4.11&charset=utf8mb4"
```
# Tip and Trick
## Change dislay's category in product to `name` instead of `id`
In /Form/ProductType.php:
```
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, [
                'class' => category::class,
'choice_label' => 'name', //doi cho này sẽ là name thay vi id
            ])
        ;
    }
```
## Change Route in Controller
```
#[Route('/')]
final class ProductController extends AbstractController{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
```