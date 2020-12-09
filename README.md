# Please read this before review
Here is the list of **possible inconsistencies** between your code-guide and this project
 * Used autoconfig + autowire because of test project, but ambigulos dependencies are passed correctly. In production terms I am also prefer to use container over magic.
 * Some private data (Bin) can be logged
 * Some configs can be not correctly structured because of app size 

I don't get the meaning of next

```text

For services, we use some suffix to represent the job of that service, usually *er:

manager

...

resolver

```

So I`ve renamed everything to have prefix manager instead of service. Not standard approach...

<hr>

**Next things to improve:**
 * Add validation for input params
 * Add some currency enum for testing correct values
 * Add application layer when it is needed =)
 * Add more accurate error handling on BL cases

**Additional things that added to task:**
 * This project was build with latest php 8 and Symfony versions
 * File reader uses generators to process big files with pagination
 * All DTO's are immutable
 * CommissionManager configured to process transactions by batches
 * Binlist configured to use async requests to process transactions by batches
 * Exchange rates response is cached by 1h
 * Output values of commissions will be rounded with precision 2
 * Scale of math operations will be 5

