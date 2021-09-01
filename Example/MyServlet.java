import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.regex.Pattern;
import javax.servlet.Servlet;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/*
Класът MyServlet играе ролята на Reverse Proxy в приложението. Той приема заявките от потребителите, и след 
филтриране на параметрите, ги пренасочва към  backend сървър на php и показва резултатите.
User < - > MyServlet  < - > votebackend.php
*/

@WebServlet("/MyServlet")
public class MyServlet extends HttpServlet implements Servlet {
	private static final long serialVersionUID = 1L;
       
    public MyServlet() {
        super();
    }

	/*
	 Тази функция обработва  Get заявките към сървъра , като ако има зададен параметър "vote" , стойността и се филтрира с 
	  Whitelis filtering. Ако тя е валидна, до votebackend.php се изпраца Get заявка със същите параметри, и върнатото от 
	  backend-а се показва на user-а. Ако стойността на парамета не отговаря на изискванията, се показва съответното съобщение.
	  От особено значение е , че request.getParameter ще върне само първата подадена за параметъра стойност:
	   http://localhost:8080/Project/voting?vote=Red&vote=45454 ще провери само стойността на vote=Red, а целия 
	   QueryString -> vote=Red&vote=45454    ще присъства в заявката към votebackend.php.
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException 
	{
		if ( request.getParameter("vote") != null)
		{
			response.setContentType("text/html");
			PrintWriter out=response.getWriter();
			
			//филтрира
			String v=request.getParameter("vote");
			if(Pattern.matches("[a-zA-Z]{1,5}",v))
			 {
				//изпраща заявка до backend сървъра
				String requestUrl = "http://localhost:80/project/votebackend.php?";
				String query = request.getQueryString();
				System.out.println(requestUrl + query);
				URL url = new URL(requestUrl + query);
				HttpURLConnection conn = (HttpURLConnection) url.openConnection();
				conn.setRequestMethod("GET");
				conn.setDoInput(true);
				conn.setRequestProperty("Accept-Charset", "UTF-8");
				
				int responseCode = conn.getResponseCode();
				if (responseCode == HttpURLConnection.HTTP_OK) 
					{ // при успешна връзка
						BufferedReader in = new BufferedReader(new InputStreamReader(
								conn.getInputStream()));
						String inputLine;
						StringBuffer res = new StringBuffer();
		
						while ((inputLine = in.readLine()) != null) 
						{
							res.append(inputLine);
						}
						in.close();
		
						// print result
						out.println("<h1>"+res.toString()+"</h1>");
					} 
					else 
					{
						System.out.println("GET request did not work");
						out.println("<h1>Server Maintanence. Please try again later.</h1>");
					}
	
			 }
			else
			 {  
				out.println("<h1>Invalid parameters!</h1>");
			 }
		       
			
		}
	}
	

}
