
The problem of static module configuration.

Symfony, Laravel, Wordpress and many other technologies have a problem they would like to solve.

All those technologies ship a core set of functionality, but they can't possibly cover every possible use-case. Instead they allow you to expand the functionality of their technology by using modules.


> Natural selection selects for maximum 'biomass', not for greatest fit to an ecosystem.

They want to make it easy as possible for people to download, install and start using those modules*, as otherwise people will give up and choose a different technology.


> A system represents someone's solution to a problem. The system doesn't solve the problem.

The solution they have all come up with is pretty common across all of them. They allow the modules to register their own config, and access the available system config through global functions or their equivalent.**


For example, Symfony allows a module to hook into the middleware though some config that looks like this:


> Once something has been identified as a problem, a solution will be suggested.


> Systems Are Seductive. They promise to do a hard job faster, better, and more easily than you could do it by yourself. But if you set up a system, you are likely to find your time and effort now being consumed in the care and feeding of the system itself. New problems are created by its very presence. Once set up, it won't go away, it grows and encroaches. It begins to do strange and wonderful things. Breaks down in ways you never thought possible. It kicks back, gets in the way, and opposes its own proper function.


So what are the problems with having the config for modules implemented like it is in Symfony/Laravel/Wordpress?


* Lack of power - can only implement what the config supports.

* Code overhead - PHP is quite fast. Java was faster.

* Dev / prod mismatch -








So, lets look at the trade-offs involved in installing modules.


> Easy to install <------------> Good.



In this case, 'good' covers things like:

* powerful, i.e. able to be customised to your exact business needs with few lines of code.

* efficient - able to be customised



> “problems are not the problem. Coping is the problem.”













* in this case, for module I mean a library, set of functions etc, that need either some other


** Service locators are equivalent to using global functions/global variables.



Systems Are Seductive. They promise to do a hard job faster, better, and more easily than you could do it by yourself. But if you set up a system, you are likely to find your time and effort now being consumed in the care and feeding of the system itself. New problems are created by its very presence. Once set up, it won't go away, it grows and encroaches. It begins to do strange and wonderful things. Breaks down in ways you never thought possible. It kicks back, gets in the way, and opposes its own proper function. Your own perspective becomes distorted by being in the system. You become anxious and push on it to make it work. Eventually you come to believe that the misbegotten product it so grudgingly delivers is what you really wanted all the time. At that point encroachment has become complete. You have become absorbed. You are now a systems person.

